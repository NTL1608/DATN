<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\BotKnowledge;
use App\Models\Schedule;
use App\Models\ScheduleTime;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\ClinicSpecialty;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\MailHelper;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $message = $request->input('message');
            $userId = $request->input('user_id') ?? 'guest-' . uniqid();

            // Validate đầu vào
            $request->validate([
                'message' => 'required|string|regex:/^[\p{L}\p{N}\p{P}\p{Zs}]+$/u',
                'user_id' => 'nullable|string'
            ]);

            // Làm sạch và đảm bảo chuỗi là UTF-8 hợp lệ
            $message = $this->ensureUtf8($message);
            if (empty($message)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tin nhắn không hợp lệ hoặc rỗng'
                ], 400);
            }

            // Lưu tin nhắn của người dùng
            $userMessage = ChatMessage::create([
                'user_id' => $userId,
                'message' => $message,
                'is_bot' => false,
                'type' => 'text'
            ]);

            // Tìm câu trả lời phù hợp
            $response = $this->findBestResponse($message, $userId);

            // Làm sạch phản hồi của bot
            $botResponse = $this->ensureUtf8($response['answer']);
            if (empty($botResponse)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể tạo phản hồi từ bot'
                ], 500);
            }

            // Lưu phản hồi của bot (thêm context để nhớ trạng thái hội thoại)
            $botMessage = ChatMessage::create([
                'user_id' => $userId,
                'message' => $botResponse,
                'is_bot' => true,
                'type' => 'text',
                'context' => json_encode($response['context'] ?? [], JSON_UNESCAPED_UNICODE)
            ]);

            return response()->json([
                'success' => true,
                'message' => $botMessage,
                'confidence' => $response['confidence'],
                'context' => $response['context'] ?? null
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error('Lỗi mã hóa JSON trong sendMessage: ' . $e->getMessage(), [
                'message' => $message,
                'user_id' => $userId,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi mã hóa dữ liệu, vui lòng thử lại'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Lỗi chat trong sendMessage: ' . $e->getMessage(), [
                'message' => $message,
                'user_id' => $userId,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ], 500);
        }
    }

    public function teachBot(Request $request)
    {
        try {
            $request->validate([
                'question' => 'required|string|regex:/^[\p{L}\p{N}\p{P}\p{Zs}]+$/u',
                'answer' => 'required|string|regex:/^[\p{L}\p{N}\p{P}\p{Zs}]+$/u'
            ]);

            $question = $this->ensureUtf8($request->input('question'));
            $answer = $this->ensureUtf8($request->input('answer'));

            if (empty($question) || empty($answer)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Câu hỏi hoặc câu trả lời không hợp lệ'
                ], 400);
            }

            // Cập nhật hoặc tạo mới câu hỏi
            $existing = BotKnowledge::whereRaw('LOWER(question) = ?', [mb_strtolower($question, 'UTF-8')])
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($existing) {
                $existing->answer = $answer;
                $existing->confidence = min($existing->confidence + 0.1, 1.0);
                $existing->updated_at = Carbon::now();
                $existing->save();
            } else {
                BotKnowledge::create([
                    'question' => $question,
                    'answer' => $answer,
                    'confidence' => 0.6
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bot đã học được câu trả lời mới'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi dạy bot: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi dạy bot'
            ], 500);
        }
    }

    private function findBestResponse($message, $userId)
    {
        $message = $this->ensureUtf8(mb_strtolower(trim($message), 'UTF-8'));
        $context = $this->getConversationContext($userId);

        // =====================================================================
        // FIX: Xác định intent TRƯỚC, sau đó mới extract các thông tin phụ
        // Tránh trường hợp nhiều handler cùng kích hoạt gây trả lời sai
        // =====================================================================
        $intent = $this->extractDetailedIntent($message);
        // Xử lý câu chào hỏi chung chung
        $greetingPatterns = [
            '/^(xin chào|chào|hello|hi|hey|alo)\b/iu',
            '/^(tôi nên làm gì|tôi cần làm gì|giúp tôi|help|bạn làm được gì|bot làm được gì)\b/iu',
            '/^(tôi muốn|toi muon|cho tôi biết|cho toi biet)$/iu',
        ];
        foreach ($greetingPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return [
                    'answer' => "Xin chào! Tôi có thể giúp bạn:\n\n• 🗓️ Đặt lịch khám — VD: \"Đặt lịch khám răng\"\n• 👨‍⚕️ Tìm bác sĩ — VD: \"Tìm bác sĩ khoa Nội\"\n• 💰 Xem giá khám — VD: \"Giá khám Tim mạch\"\n• 📋 Tra cứu lịch hẹn — VD: \"Tra cứu BK000001\"\n\nBạn cần hỗ trợ gì ạ?",
                    'confidence' => 1.0,
                    'context' => $context
                ];
            }
        }
        // Kết hợp với context từ lần trước để xác nhận đặt lịch
        if (
            preg_match('/^(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', trim($message), $timeMatch) ||
            preg_match('/^(\d{1,2}:\d{2})$/', trim($message), $timeMatch)
        ) {
            $time = $timeMatch[0]; // toàn bộ chuỗi giờ user nhập

            // Chỉ xử lý nếu context còn nhớ bác sĩ và ngày từ lần trước
            if (!empty($context['doctor_name']) && !empty($context['date'])) {
                $result = $this->confirmBooking(
                    $context['doctor_name'],
                    $context['date'],
                    $time,
                    $context
                );
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.95,
                    'context' => $result['context'] ?? $context
                ];
            }

            // Có giờ nhưng không có context bác sĩ → hỏi lại
            return [
                'answer' => 'Bạn muốn đặt lịch với bác sĩ nào? Vui lòng cho biết tên bác sĩ.',
                'confidence' => 0.8,
                'context' => $context
            ];
        }

        // Ưu tiên nhận diện intent giá khám
        if ($intent === 'doctor_price') {
            $doctorName = $this->extractDoctorName($message);
            $result = $this->handleDoctorPrice($doctorName, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            } else {
                return [
                    'answer' => 'Bạn muốn biết giá khám của bác sĩ nào? Vui lòng nhập tên bác sĩ.',
                    'confidence' => 0.7,
                    'context' => $context
                ];
            }
        }

        // Nếu intent là hỏi giá dịch vụ
        if ($intent === 'service_price') {
            $service = $this->extractService($message);
            $result = $this->handleServicePrice($service, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Nếu intent là kiểm tra lịch làm việc của bác sĩ
        if ($intent === 'doctor_availability') {
            $doctorName = $this->extractDoctorName($message);
            $result = $this->handleDoctorAvailability($doctorName, null, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Nếu intent là tra cứu thông tin booking
        if ($intent === 'booking_lookup') {
            $bookingCode = $this->extractBookingCode($message);
            $result = $this->handleBookingLookup($bookingCode, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Kiểm tra xem có phải là mã booking code không (dạng BK + 6 số)
        if (preg_match('/\b(BK\d{6})\b/i', $message, $matches)) {
            $bookingCode = strtoupper($matches[1]);
            $result = $this->handleBookingLookup($bookingCode, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.95,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Xử lý các câu hỏi giá khám không rõ ràng (không có từ khóa "bác sĩ" hoặc "dịch vụ")
        if (
            preg_match('/giá khám\s+(.+)/iu', $message, $matches) ||
            preg_match('/gia kham\s+(.+)/iu', $message, $matches)
        ) {
            $name = trim($matches[1]);

            $doctor = User::where('name', 'like', '%' . $name . '%')
                ->where('type', User::TYPE_DOCTOR)
                ->first();

            if ($doctor) {
                $result = $this->handleDoctorPrice($name, $context);
                if (!empty($result['answer'])) {
                    return [
                        'answer' => $this->ensureUtf8($result['answer']),
                        'confidence' => $result['confidence'] ?? 0.9,
                        'context' => $result['context'] ?? $context
                    ];
                }
            } else {
                $result = $this->handleServicePrice($name, $context);
                if (!empty($result['answer'])) {
                    return [
                        'answer' => $this->ensureUtf8($result['answer']),
                        'confidence' => $result['confidence'] ?? 0.9,
                        'context' => $result['context'] ?? $context
                    ];
                }
            }
        }

        $doctorName = $this->extractDoctorName($message);
        $service    = $this->extractService($message);
        $date       = $this->extractDate($message);

        // =====================================================================
        // FIX: Nếu intent là booking_with_doctor thì ưu tiên xử lý đặt lịch
        // kể cả khi user nhập "đặt lịch khám răng" (tên dịch vụ, không có bác sĩ)
        // =====================================================================
        if ($intent === 'booking_with_doctor') {
            // Nếu tìm được tên bác sĩ cụ thể
            if ($doctorName) {
                $result = $this->handleBookingWithDoctor($doctorName, $date, $context);
                if (!empty($result['answer'])) {
                    return [
                        'answer' => $this->ensureUtf8($result['answer']),
                        'confidence' => $result['confidence'] ?? 0.9,
                        'context' => $result['context'] ?? $context
                    ];
                }
            }
            // Nếu không có tên bác sĩ nhưng có dịch vụ → tìm bác sĩ theo dịch vụ
            if ($service) {
                $result = $this->handleFindDoctorByService($service, $date, $context);
                if (!empty($result['answer'])) {
                    return [
                        'answer' => $this->ensureUtf8($result['answer']),
                        'confidence' => $result['confidence'] ?? 0.9,
                        'context' => $result['context'] ?? $context
                    ];
                }
            }
            // Không có cả bác sĩ lẫn dịch vụ → hỏi lại
            return [
                'answer' => 'Bạn muốn đặt lịch khám bác sĩ nào hoặc dịch vụ gì? Vui lòng cung cấp thêm thông tin.',
                'confidence' => 0.8,
                'context' => $context
            ];
        }

        // Nếu có từ khóa tìm kiếm lịch khám với bác sĩ
        if ($doctorName && (strpos($message, 'tìm lịch') !== false || strpos($message, 'lịch khám') !== false)) {
            $result = $this->handleBookingWithDoctor($doctorName, $date, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Nếu có từ khóa tìm kiếm lịch khám với dịch vụ
        if ($service && (strpos($message, 'tìm lịch') !== false || strpos($message, 'lịch khám') !== false)) {
            $result = $this->handleFindSlotsByService($service, $date, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Xử lý các pattern mới cho tìm lịch khám dịch vụ kết hợp với ngày
        if (
            preg_match('/tôi muốn tìm lịch khám dịch vụ\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/tìm lịch khám dịch vụ\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/lịch khám dịch vụ\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/tim lich kham dich vu\s+([\w\s]+)\s+vao\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/lich kham dich vu\s+([\w\s]+)\s+vao\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/tôi muốn tìm lịch khám\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/tìm lịch khám\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu', $message, $matches) ||
            preg_match('/tim lich kham\s+([\w\s]+)\s+vao\s+([\w\s]+)/iu', $message, $matches)
        ) {
            $extractedService = trim($matches[1]);
            $extractedDate = trim($matches[2]);
            $parsedDate = $this->extractDateFromText($extractedDate);

            $result = $this->handleFindSlotsByService($extractedService, $parsedDate, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Ưu tiên: Nếu có tên bác sĩ thì trả về lịch khám còn trống của bác sĩ đó
        if ($doctorName) {
            $result = $this->handleBookingWithDoctor($doctorName, $date, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }
        }

        // Nếu có tên khoa/dịch vụ thì trả về danh sách bác sĩ có lịch trống trong khoa/dịch vụ đó
        // Nếu có tên khoa/dịch vụ thì trả về danh sách bác sĩ có lịch trống
        if ($service) {
            $result = $this->handleFindDoctorByService($service, $date, $context);

            // Chỉ return nếu câu trả lời chứa thông tin bác sĩ thực sự (có lịch)
            $noSlotPhrases = [
                'không có bác sĩ nào còn lịch',
                'không có bác sĩ nào làm việc',
                'không còn lịch',
                'chọn ngày khác',
            ];

            $hasNoSlot = false;
            foreach ($noSlotPhrases as $phrase) {
                if (mb_strpos($result['answer'] ?? '', $phrase, 0, 'UTF-8') !== false) {
                    $hasNoSlot = true;
                    break;
                }
            }

            if (!$hasNoSlot && !empty($result['answer'])) {
                // Có bác sĩ còn lịch → trả về luôn
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.9,
                    'context' => $result['context'] ?? $context
                ];
            }

            // Không có lịch → bỏ qua, để Gemini xử lý
        }
        // Giữ nguyên các logic cũ
        $scheduleResponse = $this->handleScheduleQuery($message, $context);
        if ($scheduleResponse) {
            return $scheduleResponse;
        }

        // Tìm trong bot_knowledge
        $matches = $this->findBestMatch($message);
        if (!empty($matches)) {
            return [
                'answer' => $this->ensureUtf8($matches[0]['answer']),
                'confidence' => $matches[0]['confidence'],
                'context' => $context
            ];
        }
        // Nếu message chứa tên chuyên khoa mà không có intent rõ ràng
        // → mặc định tìm bác sĩ còn lịch của chuyên khoa đó
        $bareService = $this->extractService($message);
        if ($bareService) {
            $result = $this->handleFindDoctorByService($bareService, null, $context);
            if (!empty($result['answer'])) {
                return [
                    'answer' => $this->ensureUtf8($result['answer']),
                    'confidence' => $result['confidence'] ?? 0.85,
                    'context' => $result['context'] ?? $context
                ];
            }
        }
        // Gọi Gemini AI nếu không tìm thấy câu trả lời phù hợp
        $geminiResponse = $this->callGeminiAPI($message, $context);
        if ($geminiResponse) {
            return [
                'answer' => $this->ensureUtf8($geminiResponse),
                'confidence' => 0.85,
                'context' => $context
            ];
        }

        // Câu trả lời mặc định (fallback)
        return [
            'answer' => $this->ensureUtf8($this->getDefaultResponse($message)),
            'confidence' => 0.3,
            'context' => $context
        ];
    }

    private function getConversationContext($userId)
    {
        // =====================================================================
        // FIX: Lấy context từ tin nhắn bot gần nhất để nhớ trạng thái hội thoại
        // Trước đây luôn trả về null → bot không nhớ được ngữ cảnh
        // =====================================================================
        $defaultContext = [
            'doctor_name' => null,
            'service' => null,
            'clinic' => null,
            'date' => null,
            'intent' => null,
            'step' => 1,
            'patient_id' => $userId
        ];

        try {
            // Lấy tin nhắn bot gần nhất có lưu context
            $lastBotMessage = ChatMessage::where('user_id', $userId)
                ->where('is_bot', true)
                ->whereNotNull('context')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastBotMessage && !empty($lastBotMessage->context)) {
                $savedContext = json_decode($lastBotMessage->context, true);
                if (is_array($savedContext)) {
                    Log::info('Context loaded', $savedContext);
                    // Merge với default để đảm bảo luôn có đủ các key
                    return array_merge($defaultContext, $savedContext);
                }
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy conversation context: ' . $e->getMessage(), [
                'user_id' => $userId,
                'exception' => $e
            ]);
        }

        return $defaultContext;
    }

    private function handleScheduleQuery($message, $context)
    {
        $doctorName = $this->extractDoctorName($message) ?: $context['doctor_name'];
        $date = $this->extractDate($message) ?: $context['date'];
        $service = $this->extractService($message) ?: $context['service'];
        $clinic = $this->extractClinic($message) ?: $context['clinic'];
        $intent = $this->extractDetailedIntent($message) ?: $context['intent'];

        // Nếu intent là confirm_booking và có khung giờ trong tin nhắn
        if ($intent == 'confirm_booking' && preg_match('/(\d{1,2}:\d{2})/', $message, $timeMatch)) {
            $time = $timeMatch[1] . '-' . date('H:i', strtotime($timeMatch[1] . ' +15 minutes'));
            return $this->confirmBooking($context['doctor_name'], $context['date'], $time, $context);
        }

        if ($intent == 'booking_with_doctor') {
            return $this->handleBookingWithDoctor($doctorName, $date, $context);
        } elseif ($intent == 'booking_without_doctor') {
            return $this->handleBookingWithoutDoctor($date, $context);
        } elseif ($intent == 'find_doctor_by_service') {
            return $this->handleFindDoctorByService($service, $date, $context);
        } elseif ($intent == 'find_slots_by_service') {
            return $this->handleFindSlotsByService($service, $date, $context);
        } elseif ($intent == 'doctor_availability') {
            return $this->handleDoctorAvailability($doctorName, $date, $context);
        } elseif ($intent == 'clinic_doctors') {
            return $this->handleClinicDoctors($service, $date, $context);
        } elseif ($intent == 'doctor_price') {
            return $this->handleDoctorPrice($doctorName, $context);
        } elseif ($intent == 'service_price') {
            return $this->handleServicePrice($service, $context);
        }

        return null;
    }

    private function extractDetailedIntent($message)
    {
        $patterns = [
            'booking_with_doctor' => [
                '/đặt lịch khám bác sĩ\s+([\w\s]+)/iu',
                '/tôi muốn đặt lịch với bác sĩ\s+([\w\s]+)/iu',
                '/bác sĩ\s+([\w\s]+) còn lịch không/iu',
                '/đặt lịch với bs\s+([\w\s]+)/iu',
                '/dat lich bac si\s+([\w\s]+)/iu',
                '/dat lich bs\s+([\w\s]+)/iu',
                '/dat lich dr\s+([\w\s]+)/iu',
                '/tôi muốn tìm lịch khám của bác sĩ\s+([\w\s]+)/iu',
                '/tìm lịch khám của bác sĩ\s+([\w\s]+)/iu',
                '/lịch khám của bác sĩ\s+([\w\s]+)/iu',
                '/tim lich kham bac si\s+([\w\s]+)/iu',
                '/lich kham bac si\s+([\w\s]+)/iu',
                // =====================================================================
                // FIX: Thêm các pattern cho "đặt lịch khám + tên dịch vụ"
                // Trước đây thiếu các pattern này nên câu như "đặt lịch khám răng"
                // không match intent nào → rơi xuống handler sai
                // =====================================================================
                '/tôi muốn đặt lịch khám\s+([\w\s]+)/iu',
                '/toi muon dat lich kham\s+([\w\s]+)/iu',
                '/đặt lịch khám\s+([\w\s]+)/iu',
                '/dat lich kham\s+([\w\s]+)/iu',
                '/tôi muốn đặt lịch\s+([\w\s]+)/iu',
                '/toi muon dat lich\s+([\w\s]+)/iu',
                '/đặt lịch\s+([\w\s]+)/iu',
                '/dat lich\s+([\w\s]+)/iu',
            ],
            'find_doctor_by_service' => [
                '/khoa\s+([\w\s]+) có bác sĩ nào/iu',
                '/tôi muốn khám\s+([\w\s]+)/iu',
                '/dịch vụ\s+([\w\s]+)/iu',
                '/chuyên khoa\s+([\w\s]+)/iu',
                '/tìm bác sĩ\s+([\w\s]+)/iu',
                '/tim bac si\s+([\w\s]+)/iu',
                '/bac si khoa\s+([\w\s]+)/iu',
            ],
            'find_slots_by_service' => [
                '/lịch khám còn trống khoa\s+([\w\s]+)/iu',
                '/lich kham con trong khoa\s+([\w\s]+)/iu',
                '/lich kham trong\s+([\w\s]+)/iu',
                '/tôi muốn tìm lịch khám dịch vụ\s+([\w\s]+)/iu',
                '/tìm lịch khám dịch vụ\s+([\w\s]+)/iu',
                '/lịch khám dịch vụ\s+([\w\s]+)/iu',
                '/tim lich kham dich vu\s+([\w\s]+)/iu',
                '/lich kham dich vu\s+([\w\s]+)/iu',
                '/tôi muốn tìm lịch khám dịch vụ\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu',
                '/tìm lịch khám dịch vụ\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu',
                '/lịch khám dịch vụ\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu',
                '/tim lich kham dich vu\s+([\w\s]+)\s+vao\s+([\w\s]+)/iu',
                '/lich kham dich vu\s+([\w\s]+)\s+vao\s+([\w\s]+)/iu',
                '/tôi muốn tìm lịch khám\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu',
                '/tìm lịch khám\s+([\w\s]+)\s+vào\s+([\w\s]+)/iu',
                '/tim lich kham\s+([\w\s]+)\s+vao\s+([\w\s]+)/iu',
            ],
            'doctor_availability' => [
                '/bác sĩ\s+([\w\s]+) có làm việc tuần này/iu',
                '/bác sĩ\s+([\w\s]+) có làm việc tuần này không/iu',
                '/kiểm tra lịch bác sĩ\s+([\w\s]+)/iu',
                '/lich lam viec bs\s+([\w\s]+)/iu',
                '/bác sĩ\s+([\w\s]+) có làm việc tuần này\?/iu',
                '/bác sĩ\s+([\w\s]+) có làm việc tuần này không\?/iu',
            ],
            'clinic_doctors' => [
                '/khoa\s+([\w\s]+) có các bác sĩ nào/iu',
                '/bac si khoa\s+([\w\s]+)/iu',
            ],
            'doctor_price' => [
                '/giá khám bệnh của bác sĩ\s+(.+)/iu',
                '/gia kham bac si\s+(.+)/iu',
                '/giá khám bác sĩ\s+(.+)/iu',
                '/gia kham bac si\s+(.+)/iu',
                '/bác sĩ\s+(.+?)\s+giá bao nhiêu/iu',
                '/bac si\s+(.+?)\s+gia bao nhieu/iu',
                '/giá khám\s+(.+)/iu',
                '/gia kham\s+(.+)/iu',
            ],
            'service_price' => [
                '/giá khám bệnh dịch vụ\s+(.+)/iu',
                '/gia kham dich vu\s+(.+)/iu',
                '/giá khám dịch vụ\s+(.+)/iu',
                '/giá dịch vụ\s+(.+)/iu',
                '/gia dich vu\s+(.+)/iu',
                '/dịch vụ\s+(.+?)\s+giá bao nhiêu/iu',
                '/dich vu\s+(.+?)\s+gia bao nhieu/iu',
                '/giá khám\s+(.+)/iu',
                '/gia kham\s+(.+)/iu',
                '/khoa\s+(.+?)\s+giá bao nhiêu/iu',
                '/khoa\s+(.+?)\s+gia bao nhieu/iu',
            ],
            'booking_lookup' => [
                '/tra cứu\s+([BP]K\d{6})/iu',
                '/tra cuu\s+([BP]K\d{6})/iu',
                '/kiểm tra\s+([BP]K\d{6})/iu',
                '/kiem tra\s+([BP]K\d{6})/iu',
                '/xem thông tin\s+([BP]K\d{6})/iu',
                '/xem thong tin\s+([BP]K\d{6})/iu',
                '/mã\s+([BP]K\d{6})/iu',
                '/ma\s+([BP]K\d{6})/iu',
                '/([BP]K\d{6})/iu',
            ],
            'confirm_booking' => [
                '/đặt lịch vào\s+(\d{1,2}:\d{2})/iu',
                '/xác nhận lịch\s+(\d{1,2}:\d{2})/iu',
            ],
        ];

        foreach ($patterns as $intent => $patternList) {
            foreach ($patternList as $pattern) {
                if (preg_match($pattern, $message)) {
                    return $intent;
                }
            }
        }

        return $this->extractIntent($message);
    }

    private function handleBookingWithDoctor($doctorName, $date, $context)
    {
        if (!$doctorName) {
            $context['intent'] = 'booking_with_doctor';
            return [
                'answer' => "Bạn muốn khám bác sĩ nào? Vui lòng nhập tên bác sĩ hoặc chuyên khoa/dịch vụ bạn quan tâm.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }
        $context['doctor_name'] = $this->ensureUtf8($doctorName);
        $context['date'] = $this->ensureUtf8($date ?: Carbon::tomorrow()->format('Y-m-d'));
        $slots = $this->getAvailableSlots($context['doctor_name'], $context['date'], null);
        $formattedDate = Carbon::parse($context['date'])->format('d/m/Y');
        if (strpos($slots, 'không còn lịch') !== false || strpos($slots, 'không tìm thấy') !== false) {
            return [
                'answer' => "Rất tiếc, bác sĩ {$context['doctor_name']} không còn lịch trống vào ngày $formattedDate. Bạn muốn chọn bác sĩ khác hoặc ngày khác không?",
                'context' => $context,
                'confidence' => 0.9
            ];
        }
        $context['intent'] = 'confirm_booking';
        $context['step'] = 2;
        return [
            'answer' => "Bác sĩ {$context['doctor_name']} còn các khung giờ trống vào ngày $formattedDate:\n\n$slots\n\nBạn muốn đặt lịch vào khung giờ nào?",
            'context' => $context,
            'confidence' => 0.95
        ];
    }

    private function handleBookingWithoutDoctor($date, $context)
    {
        $context['date'] = $this->ensureUtf8($date ?: Carbon::tomorrow()->format('Y-m-d'));
        $context['intent'] = 'booking_without_doctor';
        $context['step'] = 1;

        $specialties = Specialty::pluck('name')->map(function ($name) {
            return $this->ensureUtf8($name);
        })->implode(', ');
        return [
            'answer' => "Bạn muốn khám chuyên khoa nào? Chúng tôi có: $specialties",
            'context' => $context,
            'confidence' => 0.9
        ];
    }

    private function handleFindDoctorByService($service, $date, $context)
    {
        if (!$service) {
            $context['intent'] = 'find_doctor_by_service';
            $services = Specialty::pluck('name')->map(function ($name) {
                return $this->ensureUtf8($name);
            })->implode(', ');
            return [
                'answer' => "Bạn muốn tìm bác sĩ khám dịch vụ nào? Chúng tôi có: $services",
                'context' => $context,
                'confidence' => 0.9
            ];
        }
        $context['service'] = $this->ensureUtf8($service);
        $context['date'] = $this->ensureUtf8($date ?: Carbon::tomorrow()->format('Y-m-d'));
        $doctors = $this->getDoctorsByService($context['service']);
        $availableDoctors = [];
        $formattedDate = Carbon::parse($context['date'])->format('d/m/Y');
        foreach ($doctors as $doctor) {
            $slots = $this->getAvailableSlots($doctor->name, $context['date'], $context['service']);
            if (strpos($slots, 'không còn lịch') === false && strpos($slots, 'không tìm thấy') === false) {
                $availableDoctors[] = $slots;
            }
        }
        if (empty($availableDoctors)) {
            return [
                'answer' => "Trong ngày $formattedDate, chuyên khoa {$context['service']} không có bác sĩ nào còn lịch trống. Bạn muốn chọn ngày khác hoặc chuyên khoa khác không?",
                'context' => $context,
                'confidence' => 0.9
            ];
        }
        $doctorList = implode("\n\n", $availableDoctors);
        return [
            'answer' => "Trong ngày $formattedDate, chuyên khoa {$context['service']} có các bác sĩ sau còn lịch khám:\n\n$doctorList\n\nBạn muốn đặt với bác sĩ nào hoặc chọn khung giờ nào?",
            'context' => $context,
            'confidence' => 0.95
        ];
    }

    private function handleFindSlotsByService($service, $date, $context)
    {
        if (!$service) {
            $context['intent'] = 'find_slots_by_service';
            $services = Specialty::pluck('name')->map(function ($name) {
                return $this->ensureUtf8($name);
            })->implode(', ');

            return [
                'answer' => "Bạn muốn tìm lịch khám còn trống dịch vụ gì? Chúng tôi có: $services",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $context['service'] = $this->ensureUtf8($service);
        $context['date'] = $this->ensureUtf8($date ?: Carbon::tomorrow()->format('Y-m-d'));

        $doctors = $this->getDoctorsByService($context['service']);
        $response = [];
        $formattedDate = Carbon::parse($context['date'])->format('d/m/Y');

        foreach ($doctors as $doctor) {
            $slots = $this->getAvailableSlots($doctor->name, $context['date'], $context['service']);
            if (strpos($slots, 'không còn lịch') === false && strpos($slots, 'không tìm thấy') === false) {
                $response[] = $slots;
            }
        }

        if (empty($response)) {
            return [
                'answer' => "Trong ngày $formattedDate, dịch vụ {$context['service']} không còn lịch khám trống nào.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $responseText = "Trong ngày $formattedDate, dịch vụ {$context['service']} còn các lịch khám trống:\n\n" . implode("\n\n", $response);
        return [
            'answer' => $responseText,
            'context' => $context,
            'confidence' => 0.9
        ];
    }

    private function handleDoctorAvailability($doctorName, $date, $context)
    {
        if (!$doctorName) {
            $context['intent'] = 'doctor_availability';
            return [
                'answer' => "Bạn muốn kiểm tra lịch làm việc của bác sĩ nào?",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $context['doctor_name'] = $this->ensureUtf8($doctorName);
        $context['date'] = $this->ensureUtf8($date ?: Carbon::today()->startOfWeek()->format('Y-m-d'));

        $availability = $this->getDoctorAvailability($context['doctor_name'], $context['date']);

        return [
            'answer' => $availability,
            'context' => $context,
            'confidence' => 0.9
        ];
    }

    private function handleClinicDoctors($service, $date, $context)
    {
        if (!$service) {
            $context['intent'] = 'clinic_doctors';
            $services = Specialty::pluck('name')->map(function ($name) {
                return $this->ensureUtf8($name);
            })->implode(', ');
            return [
                'answer' => "Bạn muốn biết chuyên khoa nào có bác sĩ làm việc? Chúng tôi có: $services",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $context['service'] = $this->ensureUtf8($service);
        $context['date'] = $this->ensureUtf8($date ?: Carbon::tomorrow()->format('Y-m-d'));

        $doctors = User::whereHas('specialties', function ($q) use ($service) {
            $q->where('specialties.name', 'like', '%' . $service . '%');
        })
            ->where('type', User::TYPE_DOCTOR)
            ->get();

        if ($doctors->isEmpty()) {
            $formattedDate = Carbon::parse($context['date'])->format('d/m/Y');
            return [
                'answer' => "Trong ngày $formattedDate, chuyên khoa {$context['service']} không có bác sĩ nào làm việc.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $workingDoctors = [];
        foreach ($doctors as $doctor) {
            $schedules = Schedule::where('doctor_id', $doctor->id)
                ->where('date_schedule', $context['date'])
                ->where('status', 1)
                ->whereRaw('current_number < max_number')
                ->get();

            if ($schedules->isNotEmpty()) {
                $workingDoctors[] = $this->ensureUtf8($doctor->name);
            }
        }

        $formattedDate = Carbon::parse($context['date'])->format('d/m/Y');
        if (empty($workingDoctors)) {
            return [
                'answer' => "Trong ngày $formattedDate, chuyên khoa {$context['service']} không có bác sĩ nào còn lịch trống.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $doctorList = implode(', ', $workingDoctors);
        return [
            'answer' => "Trong ngày $formattedDate, chuyên khoa {$context['service']} có các bác sĩ $doctorList làm việc.",
            'context' => $context,
            'confidence' => 0.9
        ];
    }

    private function handleDoctorPrice($doctorName, $context)
    {
        if (!$doctorName) {
            $context['intent'] = 'doctor_price';
            return [
                'answer' => "Bạn muốn biết giá khám của bác sĩ nào?",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $context['doctor_name'] = $this->ensureUtf8($doctorName);

        $doctor = User::where('name', 'like', '%' . $context['doctor_name'] . '%')
            ->where('type', User::TYPE_DOCTOR)
            ->select('id', 'name', 'price_min', 'price_max')
            ->first();

        if (!$doctor) {
            return [
                'answer' => "Không tìm thấy thông tin bác sĩ {$context['doctor_name']}.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $priceMin = $doctor->price_min ?? 200000;
        $priceMax = $doctor->price_max ?? $priceMin;

        if ($priceMin == $priceMax) {
            $formattedPrice = number_format($priceMin);
            $priceText = "Số tiền khám của bác sĩ {$context['doctor_name']} trên một lần khám là $formattedPrice VNĐ.";
        } else {
            $formattedMin = number_format($priceMin);
            $formattedMax = number_format($priceMax);
            $priceText = "Số tiền khám của bác sĩ {$context['doctor_name']} trên một lần khám dao động từ $formattedMin VNĐ đến $formattedMax VNĐ.";
        }

        return [
            'answer' => $priceText,
            'context' => $context,
            'confidence' => 0.9
        ];
    }

    private function handleServicePrice($service, $context)
    {
        if (!$service) {
            $context['intent'] = 'service_price';
            $services = Specialty::pluck('name')->map(function ($name) {
                return $this->ensureUtf8($name);
            })->implode(', ');
            return [
                'answer' => "Bạn muốn biết giá dịch vụ nào? Chúng tôi có: $services",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $context['service'] = $this->ensureUtf8($service);

        $specialty = Specialty::where('name', 'like', '%' . $service . '%')->first();

        if (!$specialty) {
            $keywords = $this->extractKeywords($service);
            $bestMatch = null;
            $bestScore = 0;

            $specialties = Specialty::all();

            foreach ($specialties as $spec) {
                $score = 0;
                $specialtyName = mb_strtolower($spec->name, 'UTF-8');

                foreach ($keywords as $keyword) {
                    if (mb_strpos($specialtyName, $keyword, 0, 'UTF-8') !== false) {
                        $score += 2;
                    }
                }

                $lengthSimilarity = 1 - abs(mb_strlen($service, 'UTF-8') - mb_strlen($specialtyName, 'UTF-8')) / max(mb_strlen($service, 'UTF-8'), mb_strlen($specialtyName, 'UTF-8'));
                $score += $lengthSimilarity;

                if ($score > $bestScore) {
                    $bestMatch = $spec;
                    $bestScore = $score;
                }
            }

            if ($bestMatch && $bestScore > 2) {
                $specialty = $bestMatch;
                $context['service'] = $this->ensureUtf8($specialty->name);
            }
        } else {
            $context['service'] = $this->ensureUtf8($specialty->name);
        }

        if (!$specialty) {
            Log::info('Service price not found', [
                'original_service' => $service,
                'context_service' => $context['service'],
                'available_specialties' => Specialty::pluck('name')->toArray()
            ]);

            return [
                'answer' => "Không tìm thấy thông tin giá dịch vụ {$context['service']}. Vui lòng kiểm tra lại tên dịch vụ hoặc liên hệ trực tiếp để được tư vấn.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $servicePrice = $specialty->price ?? 200000;
        $formattedPrice = number_format($servicePrice);
        $priceText = "Giá khám dịch vụ {$context['service']} trên một lần khám là $formattedPrice VNĐ.";

        $doctorCount = User::whereHas('specialties', function ($q) use ($specialty) {
            $q->where('specialties.id', $specialty->id);
        })->where('type', User::TYPE_DOCTOR)->count();

        $priceText .= "\n\n📊 Có {$doctorCount} bác sĩ chuyên khoa {$context['service']} đang hoạt động.";

        return [
            'answer' => $priceText,
            'context' => $context,
            'confidence' => 0.9
        ];
    }

    private function confirmBooking($doctorName, $date, $time, $context)
    {
        try {
            $doctor = User::where('name', 'like', '%' . $doctorName . '%')
                ->where('type', User::TYPE_DOCTOR)
                ->firstOrFail();
            $schedule = Schedule::where('doctor_id', $doctor->id)
                ->where('date_schedule', $date)
                ->where('status', 1)
                ->whereRaw('current_number < max_number')
                ->firstOrFail();
            $scheduleTime = ScheduleTime::where('schedule_id', $schedule->id)
                ->where('time_schedule', $time)
                ->firstOrFail();
            $patient = User::where('user_code', $context['patient_id'])
                ->where('type', User::TYPE_PATIENT)
                ->firstOrFail();

            $booking = Booking::create([
                'booking_code' => 'BK' . str_pad(Booking::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'schedule_time_id' => $scheduleTime->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'specialty_id' => $doctor->specialties()->first()->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'birthday' => $patient->birthday,
                'book_for' => User::BOOK_FOR[1],
                'city_id' => $patient->city_id,
                'district_id' => $patient->district_id,
                'street_id' => $patient->street_id,
                'address' => $patient->address,
                'date_booking' => $date,
                'time_booking' => explode('-', $time)[0],
                'time_type' => $schedule->time_type,
                'price' => $doctor->price_min,
                'status' => 1,
                'note' => ''
            ]);

            $schedule->increment('current_number');
            if ($schedule->current_number >= $schedule->max_number) {
                $schedule->update(['status' => 2]);
            }

            if ($booking->status == 7) {
                $dataMail = [
                    'id' => $booking->id,
                    'name' => $booking->name,
                    'specialty' => $booking->specialty->name,
                    'name_doctor' => $booking->doctor->name,
                    'email' => $booking->email,
                    'date_booking' => $booking->date_booking,
                    'time_booking' => $booking->time_booking,
                    'price' => $booking->price,
                    'status' => 'Lịch khám của bạn đã được đăng ký thành công',
                    'number' => $booking->number,
                    'confirm' => false,
                ];
                MailHelper::sendMail($dataMail);
            }

            $formattedDate = Carbon::parse($date)->format('d/m/Y');
            return [
                'answer' => "Đã đặt lịch thành công với bác sĩ {$doctor->name} vào {$time} ngày {$formattedDate}. Mã lịch hẹn: {$booking->booking_code}.",
                'context' => $context,
                'confidence' => 0.95
            ];
        } catch (\Exception $e) {
            Log::error('Lỗi khi đặt lịch: ' . $e->getMessage(), [
                'doctorName' => $doctorName,
                'date' => $date,
                'time' => $time,
                'context' => $context,
                'exception' => $e
            ]);
            return [
                'answer' => "Có lỗi khi đặt lịch. Vui lòng kiểm tra lại thông tin và thử lại.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }
    }

    private function extractIntent($message)
    {
        $message = $this->ensureUtf8(mb_strtolower($message, 'UTF-8'));
        $messageNoAccent = function_exists('safeTitle') ? safeTitle($message) : $message;
        $keywords = [
            'booking_with_doctor' => ['đặt lịch khám bác sĩ', 'dat lich kham bac si', 'dat lich bs', 'dat lich dr', 'dat lich bac si', 'dat lich bs', 'dat lich dr'],
            'booking_without_doctor' => ['tôi muốn khám vào', 'toi muon kham vao', 'dat lich kham', 'dat lich'],
            'find_doctor_by_service' => ['tìm bác sĩ khám dịch vụ', 'tim bac si kham dich vu', 'tim bac si', 'tim bs', 'tim dr'],
            'find_slots_by_service' => ['tìm lịch khám còn trống dịch vụ', 'tim lich kham con trong dich vu', 'tim lich kham', 'lich kham trong'],
            'doctor_availability' => ['bác sĩ có làm việc tuần này', 'co lam viec tuan nay', 'kiem tra lich bac si', 'lich lam viec bs', 'lich lam viec dr'],
            'clinic_doctors' => ['khoa có các bác sĩ nào làm việc', 'bac si khoa', 'bs khoa', 'dr khoa'],
            'doctor_price' => ['giá khám bệnh của bác sĩ', 'gia kham benh cua bac si', 'gia kham bs', 'gia kham dr'],
            'service_price' => ['giá khám bệnh dịch vụ', 'gia kham benh dich vu', 'gia dich vu', 'gia kham'],
            'confirm_booking' => ['đặt lịch vào', 'dat lich vao', 'xac nhan lich', 'xac nhan dat lich']
        ];
        foreach ($keywords as $intent => $terms) {
            foreach ($terms as $term) {
                if (preg_match("/$term/iu", $message) || preg_match("/$term/iu", $messageNoAccent)) {
                    return $intent;
                }
            }
        }
        return null;
    }

    private function extractClinic($message)
    {
        $message = $this->ensureUtf8(mb_strtolower($message, 'UTF-8'));
        $messageNoAccent = function_exists('safeTitle') ? safeTitle($message) : $message;
        $clinics = Clinic::pluck('name')->map(function ($name) {
            return [
                'original' => $this->ensureUtf8(mb_strtolower($name, 'UTF-8')),
                'no_accent' => function_exists('safeTitle') ? safeTitle($name) : $name
            ];
        })->toArray();
        foreach ($clinics as $clinic) {
            if (
                mb_strpos($message, $clinic['original'], 0, 'UTF-8') !== false ||
                mb_strpos($messageNoAccent, $clinic['no_accent']) !== false
            ) {
                return $clinic['original'];
            }
        }
        $patterns = [
            '/phòng khám\s+([^\s,]+)/iu',
            '/clinic\s+([^\s,]+)/iu',
            '/([^\s,]+)\s+phòng khám/iu',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $clinicName = $this->ensureUtf8(trim($matches[1]));
                $clinicNoAccent = function_exists('safeTitle') ? safeTitle($clinicName) : $clinicName;
                foreach ($clinics as $clinic) {
                    if (
                        mb_strpos($clinic['original'], $clinicName, 0, 'UTF-8') !== false ||
                        mb_strpos($clinic['no_accent'], $clinicNoAccent) !== false ||
                        $this->calculateSimilarity($clinicName, $clinic['original']) > 0.85 ||
                        $this->calculateSimilarity($clinicNoAccent, $clinic['no_accent']) > 0.85
                    ) {
                        return $clinic['original'];
                    }
                }
            }
        }
        return null;
    }

    private function extractDoctorName($message)
    {
        $message = $this->ensureUtf8(mb_strtolower(trim($message), 'UTF-8'));
        $messageNoAccent = function_exists('safeTitle') ? safeTitle($message) : $message;

        $patterns = [
            '/bác sĩ\s+(.+?)(?=\s*(?:có làm việc|kiểm tra|lịch|ngày|tuần|vào|\b|$))/iu',
            '/bs\s+(.+?)(?=\s*(?:có làm việc|kiểm tra|lịch|ngày|tuần|vào|\b|$))/iu',
            '/dr\s+(.+?)(?=\s*(?:có làm việc|kiểm tra|lịch|ngày|tuần|vào|\b|$))/iu',
            '/doctor\s+(.+?)(?=\s*(?:có làm việc|kiểm tra|lịch|ngày|tuần|vào|\b|$))/iu',
            '/(.+?)\s+bác sĩ/iu',
            '/(.+?)\s+bs/iu',
            '/(.+?)\s+dr/iu',
            '/(.+?)\s+doctor/iu',
        ];

        $doctors = User::where('type', User::TYPE_DOCTOR)
            ->pluck('name')
            ->map(function ($name) {
                return [
                    'original' => $this->ensureUtf8(trim($name)),
                    'no_accent' => function_exists('safeTitle') ? safeTitle($name) : $name
                ];
            })->toArray();

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $extractedName = $this->ensureUtf8(trim($matches[1]));
                $extractedNoAccent = function_exists('safeTitle') ? safeTitle($extractedName) : $extractedName;
                foreach ($doctors as $doctor) {
                    if (
                        mb_strpos(mb_strtolower($doctor['original']), mb_strtolower($extractedName), 0, 'UTF-8') !== false ||
                        mb_strpos($doctor['no_accent'], $extractedNoAccent) !== false ||
                        $this->calculateSimilarity($extractedName, $doctor['original']) > 0.85 ||
                        $this->calculateSimilarity($extractedNoAccent, $doctor['no_accent']) > 0.85
                    ) {
                        return $doctor['original'];
                    }
                }
            }
        }

        foreach ($doctors as $doctor) {
            if (
                mb_strpos($message, mb_strtolower($doctor['original']), 0, 'UTF-8') !== false ||
                mb_strpos($messageNoAccent, $doctor['no_accent']) !== false
            ) {
                return $doctor['original'];
            }
        }
        return null;
    }

    private function extractDate($message)
    {
        try {
            $message = $this->ensureUtf8(mb_strtolower($message, 'UTF-8'));
            if (empty($message)) {
                return Carbon::tomorrow()->format('Y-m-d');
            }

            $dateMap = [
                'hôm nay' => Carbon::today(),
                'ngày mai' => Carbon::tomorrow(),
                'mai' => Carbon::tomorrow(),
                'ngày kia' => Carbon::tomorrow()->addDay(),
                'thứ hai' => Carbon::now()->next(Carbon::MONDAY),
                'thứ ba' => Carbon::now()->next(Carbon::TUESDAY),
                'thứ tư' => Carbon::now()->next(Carbon::WEDNESDAY),
                'thứ năm' => Carbon::now()->next(Carbon::THURSDAY),
                'thứ sáu' => Carbon::now()->next(Carbon::FRIDAY),
                'thứ bảy' => Carbon::now()->next(Carbon::SATURDAY),
                'tuần này' => Carbon::now()->startOfWeek(),
                'tuần sau' => Carbon::now()->addWeek()->startOfWeek(),
            ];

            foreach ($dateMap as $keyword => $date) {
                if (mb_strpos($message, $keyword, 0, 'UTF-8') !== false) {
                    return $date->format('Y-m-d');
                }
            }

            $patterns = [
                '/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})/u',
                '/ngày\s+(\d{1,2})\s+tháng\s+(\d{1,2})\s*(?:năm\s+(\d{4}))?/iu',
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $message, $matches)) {
                    $day = (int)$matches[1];
                    $month = (int)$matches[2];
                    $year = isset($matches[3]) ? (int)$matches[3] : Carbon::now()->year;

                    if (checkdate($month, $day, $year)) {
                        return Carbon::create($year, $month, $day)->format('Y-m-d');
                    }
                }
            }

            return Carbon::tomorrow()->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Lỗi trong extractDate: ' . $e->getMessage(), [
                'message' => $message,
                'exception' => $e
            ]);
            return Carbon::tomorrow()->format('Y-m-d');
        }
    }

    private function extractDateFromText($dateText)
    {
        try {
            $dateText = $this->ensureUtf8(mb_strtolower(trim($dateText), 'UTF-8'));
            if (empty($dateText)) {
                return Carbon::tomorrow()->format('Y-m-d');
            }

            $dateMap = [
                'hôm nay' => Carbon::today(),
                'ngày mai' => Carbon::tomorrow(),
                'mai' => Carbon::tomorrow(),
                'ngày kia' => Carbon::tomorrow()->addDay(),
                'thứ hai' => Carbon::now()->next(Carbon::MONDAY),
                'thứ ba' => Carbon::now()->next(Carbon::TUESDAY),
                'thứ tư' => Carbon::now()->next(Carbon::WEDNESDAY),
                'thứ năm' => Carbon::now()->next(Carbon::THURSDAY),
                'thứ sáu' => Carbon::now()->next(Carbon::FRIDAY),
                'thứ bảy' => Carbon::now()->next(Carbon::SATURDAY),
                'tuần này' => Carbon::now()->startOfWeek(),
                'tuần sau' => Carbon::now()->addWeek()->startOfWeek(),
            ];

            foreach ($dateMap as $keyword => $date) {
                if (mb_strpos($dateText, $keyword, 0, 'UTF-8') !== false) {
                    return $date->format('Y-m-d');
                }
            }

            $patterns = [
                '/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})/u',
                '/ngày\s+(\d{1,2})\s+tháng\s+(\d{1,2})\s*(?:năm\s+(\d{4}))?/iu',
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $dateText, $matches)) {
                    $day = (int)$matches[1];
                    $month = (int)$matches[2];
                    $year = isset($matches[3]) ? (int)$matches[3] : Carbon::now()->year;

                    if (checkdate($month, $day, $year)) {
                        return Carbon::create($year, $month, $day)->format('Y-m-d');
                    }
                }
            }

            return Carbon::tomorrow()->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Lỗi trong extractDateFromText: ' . $e->getMessage(), [
                'dateText' => $dateText,
                'exception' => $e
            ]);
            return Carbon::tomorrow()->format('Y-m-d');
        }
    }

    private function extractService($message)
    {
        $message = $this->ensureUtf8(mb_strtolower($message, 'UTF-8'));
        $messageNoAccent = function_exists('safeTitle') ? safeTitle($message) : $message;
        $specialties = Specialty::pluck('name')->map(function ($name) {
            return [
                'original' => $this->ensureUtf8(mb_strtolower($name, 'UTF-8')),
                'no_accent' => function_exists('safeTitle') ? safeTitle($name) : $name
            ];
        })->toArray();

        foreach ($specialties as $specialty) {
            if (
                mb_strpos($message, $specialty['original'], 0, 'UTF-8') !== false ||
                mb_strpos($messageNoAccent, $specialty['no_accent']) !== false
            ) {
                return $specialty['original'];
            }
        }

        $patterns = [
            '/dịch vụ\s+(.+)/iu',
            '/khám\s+(.+)/iu',
            '/chuyên khoa\s+(.+)/iu',
            '/(.+?)\s+dịch vụ/iu',
            '/(.+?)\s+chuyên khoa/iu',
            '/tìm lịch khám\s+(.+)/iu',
            '/tim lich kham\s+(.+)/iu',
            '/lịch khám\s+(.+)/iu',
            '/lich kham\s+(.+)/iu',
            '/tôi muốn tìm lịch khám\s+(.+)/iu',
            '/toi muon tim lich kham\s+(.+)/iu',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $serviceName = $this->ensureUtf8(trim($matches[1]));
                $serviceNoAccent = function_exists('safeTitle') ? safeTitle($serviceName) : $serviceName;

                foreach ($specialties as $specialty) {
                    if (
                        mb_strpos($specialty['original'], $serviceName, 0, 'UTF-8') !== false ||
                        mb_strpos($specialty['no_accent'], $serviceNoAccent) !== false
                    ) {
                        return $specialty['original'];
                    }
                }

                $keywords = $this->extractKeywords($serviceName);
                $bestMatch = null;
                $bestScore = 0;

                foreach ($specialties as $specialty) {
                    $score = 0;
                    $specialtyName = $specialty['original'];
                    $specialtyNoAccent = $specialty['no_accent'];

                    foreach ($keywords as $keyword) {
                        if (mb_strpos($specialtyName, $keyword, 0, 'UTF-8') !== false) {
                            $score += 2;
                        } elseif (mb_strpos($specialtyNoAccent, $keyword) !== false) {
                            $score += 1;
                        }
                    }

                    $lengthSimilarity = 1 - abs(mb_strlen($serviceName, 'UTF-8') - mb_strlen($specialtyName, 'UTF-8')) / max(mb_strlen($serviceName, 'UTF-8'), mb_strlen($specialtyName, 'UTF-8'));
                    $score += $lengthSimilarity;

                    if ($score > $bestScore) {
                        $bestMatch = $specialty['original'];
                        $bestScore = $score;
                    }
                }

                if ($bestMatch && $bestScore > 2) {
                    return $bestMatch;
                }

                $bestMatch = null;
                $bestSimilarity = 0;
                foreach ($specialties as $specialty) {
                    $similarity1 = $this->calculateSimilarity($serviceName, $specialty['original']);
                    $similarity2 = $this->calculateSimilarity($serviceNoAccent, $specialty['no_accent']);
                    $maxSimilarity = max($similarity1, $similarity2);

                    if ($maxSimilarity > 0.5 && $maxSimilarity > $bestSimilarity) {
                        $bestMatch = $specialty['original'];
                        $bestSimilarity = $maxSimilarity;
                    }
                }

                if ($bestMatch) {
                    return $bestMatch;
                }
            }
        }

        return null;
    }

    private function extractBookingCode($message)
    {
        $message = $this->ensureUtf8(mb_strtoupper(trim($message), 'UTF-8'));

        if (preg_match('/\b([BP]K\d{6})\b/i', $message, $matches)) {
            return strtoupper($matches[1]);
        }

        return null;
    }

    private function handleBookingLookup($bookingCode, $context)
    {
        if (!$bookingCode) {
            $context['intent'] = 'booking_lookup';
            return [
                'answer' => "Bạn muốn tra cứu thông tin lịch khám nào? Vui lòng nhập mã lịch hẹn (ví dụ: BK000001 hoặc PK000001).",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $context['booking_code'] = $this->ensureUtf8($bookingCode);

        $booking = Booking::with(['doctor', 'patient', 'specialty'])
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return [
                'answer' => "Không tìm thấy thông tin lịch khám với mã {$bookingCode}. Vui lòng kiểm tra lại mã lịch hẹn.",
                'context' => $context,
                'confidence' => 0.9
            ];
        }

        $status = Booking::STATUS[$booking->status] ?? 'Không xác định';
        $patientName = $this->ensureUtf8($booking->name ?? 'Chưa cập nhật');
        $citizenId = $booking->citizen_id_number ?? 'Chưa cập nhật';
        $insuranceCard = $booking->insurance_card_number ?? 'Chưa cập nhật';
        $clinic = $this->ensureUtf8($booking->doctor->clinic->name ?? 'Chưa cập nhật');
        $specialty = $this->ensureUtf8($booking->specialty->name ?? 'Chưa cập nhật');
        $doctorName = $this->ensureUtf8($booking->doctor->name ?? 'Chưa cập nhật');
        $dateBooking = Carbon::parse($booking->date_booking)->format('d/m/Y');
        $timeBooking = $booking->time_booking ?? 'Chưa cập nhật';
        $price = number_format($booking->price ?? 0);

        $response = "📋 <strong>THÔNG TIN LỊCH KHÁM - {$bookingCode}</strong>\n\n";
        $response .= "🏥 <strong>Trạng thái:</strong> {$status}\n\n";
        $response .= "👤 <strong>Tên bệnh nhân:</strong> {$patientName}\n";
        $response .= "🆔 <strong>Số CCCD:</strong> {$citizenId}\n";
        $response .= "💳 <strong>Số thẻ BHYT:</strong> {$insuranceCard}\n\n";
        $response .= "🏥 <strong>Khoa khám bệnh:</strong> {$clinic}\n";
        $response .= "🩺 <strong>Dịch vụ khám:</strong> {$specialty}\n";
        $response .= "👨‍⚕️ <strong>Bác sĩ:</strong> {$doctorName}\n";
        $response .= "📅 <strong>Ngày khám:</strong> {$dateBooking}\n";
        $response .= "⏰ <strong>Giờ khám:</strong> {$timeBooking}\n";
        $response .= "💰 <strong>Giá khám:</strong> {$price} VNĐ\n";

        if (!empty($booking->note)) {
            $note = $this->ensureUtf8($booking->note);
            $response .= "\n📝 <strong>Ghi chú:</strong> {$note}";
        }

        return [
            'answer' => $response,
            'context' => $context,
            'confidence' => 0.95
        ];
    }

    private function extractKeywords($serviceName)
    {
        $serviceName = $this->ensureUtf8(mb_strtolower(trim($serviceName), 'UTF-8'));

        $stopWords = [
            'và', 'va', 'của', 'cua', 'sau', 'trước', 'truoc', 'trong', 'ngoài', 'ngoai',
            'với', 'voi', 'cho', 'từ', 'tu', 'đến', 'den', 'bệnh', 'benh', 'nhân', 'nhan',
            'bệnh nhân', 'benh nhan', 'quản lý', 'quan ly', 'tư vấn', 'tu van',
            'can thiệp', 'can thiep', 'tim mạch', 'tim mach', 'tim', 'mạch', 'mach'
        ];

        $words = preg_split('/[\s,]+/', $serviceName);
        $keywords = [];

        foreach ($words as $word) {
            $word = trim($word);
            if (mb_strlen($word, 'UTF-8') > 2 && !in_array($word, $stopWords)) {
                $keywords[] = $word;
            }
        }

        $importantPhrases = [
            'tim mạch', 'tư vấn', 'quản lý', 'can thiệp', 'hóa trị', 'xạ trị',
            'ung thư', 'mạch vành', 'suy tim', 'điện tâm đồ', 'siêu âm tim',
            'huyết áp', 'mỡ máu', 'gan nhiễm mỡ', 'đau đầu', 'rối loạn tiền đình',
            'tai biến', 'đột quỵ', 'thần kinh thực vật', 'đau vai gáy', 'thoái hóa khớp',
            'cột sống', 'thoát vị đĩa đệm', 'gout', 'loãng xương', 'đau bụng',
            'đầy hơi', 'khó tiêu', 'viêm gan', 'xơ gan', 'nội soi', 'dạ dày',
            'đại tràng', 'sỏi thận', 'bí tiểu', 'tiểu buốt', 'tuyến tiền liệt',
            'khám thai', 'siêu âm thai', 'viêm nhiễm phụ khoa', 'kinh nguyệt',
            'vô sinh', 'hiếm muộn', 'viêm phổi', 'viêm phế quản', 'dinh dưỡng',
            'tiêm chủng', 'mụn', 'viêm da', 'dị ứng', 'nấm', 'lang ben', 'zona',
            'chăm sóc da', 'thẩm mỹ', 'viêm mũi xoang', 'đau họng', 'ù tai',
            'viêm tai giữa', 'viêm amidan', 'nội soi TMH', 'rối loạn sắc tố da',
            'nám', 'tàn nhang', 'hút mũi', 'khí dung', 'làm thuốc tai', 'tiêu chảy',
            'rối loạn tiêu hóa', 'bệnh lý về mắt', 'nhãn nhi', 'phẫu thuật thẩm mỹ',
            'thủy tinh thể'
        ];

        foreach ($importantPhrases as $phrase) {
            if (mb_strpos($serviceName, $phrase, 0, 'UTF-8') !== false) {
                $keywords[] = $phrase;
            }
        }

        return array_unique($keywords);
    }

    private function getAvailableSlots($doctorName, $date, $service)
    {
        try {
            $doctorName = $this->ensureUtf8($doctorName);
            $date       = $this->ensureUtf8($date);
            $date       = Carbon::parse($date)->format('Y-m-d');
            $service    = $this->ensureUtf8($service);

            $now        = Carbon::now();
            $dateCarbon = Carbon::parse($date);

            $query = Schedule::with(['doctor', 'times' => function ($q) {
                $q->where('status', 0);
            }])
                ->where('date_schedule', $date)
                ->where('status', 1)
                ->whereRaw('current_number < max_number');

            if ($doctorName) {
                $query->whereHas('doctor', function ($q) use ($doctorName) {
                    $q->where('name', 'like', '%' . $doctorName . '%')
                        ->where('type', User::TYPE_DOCTOR);
                });
            }

            if ($service) {
                $query->whereHas('doctor.specialties', function ($q) use ($service) {
                    $q->where('specialties.name', 'like', '%' . $service . '%');
                });
            }

            if ($dateCarbon->isToday() && $now->gte($dateCarbon->copy()->setTime(17, 0))) {
                $schedules = $query->get();

                if ($schedules->isEmpty()) {
                    $suffix        = ($doctorName ? " với bác sĩ $doctorName" : "") . ($service ? " cho dịch vụ $service" : "");
                    $formattedDate = Carbon::parse($date)->format('d/m/Y');
                    return "Không tìm thấy lịch khám còn trống$suffix vào ngày $formattedDate.";
                }

                $response   = [];
                $seenDoctor = [];

                foreach ($schedules as $schedule) {
                    if (!$schedule->doctor) {
                        continue;
                    }

                    $docId = $schedule->doctor->id;
                    if (in_array($docId, $seenDoctor, true)) {
                        continue;
                    }
                    $seenDoctor[] = $docId;

                    $doctorDisplayName = $this->ensureUtf8($schedule->doctor->name);
                    $response[] = "👨‍⚕️ <strong>Bác sĩ {$doctorDisplayName}</strong>: Đã hết thời gian làm việc, vui lòng đặt lịch khám vào ngày khác!";
                }

                return empty($response)
                    ? "Đã hết thời gian làm việc, vui lòng đặt lịch khám vào ngày khác!"
                    : implode("\n", $response);
            }

            $schedules = $query->get();

            if ($schedules->isEmpty()) {
                $suffix        = ($doctorName ? " với bác sĩ $doctorName" : "") . ($service ? " cho dịch vụ $service" : "");
                $formattedDate = Carbon::parse($date)->format('d/m/Y');
                return "Không tìm thấy lịch khám còn trống$suffix vào ngày $formattedDate.";
            }

            $response = [];

            foreach ($schedules as $schedule) {
                if (!$schedule->doctor) {
                    continue;
                }

                $doctorDisplayName = $this->ensureUtf8($schedule->doctor->name);
                $maxNumber         = $schedule->max_number;

                $timeDetails = [];

                foreach ($schedule->times as $time) {
                    $timeSchedule  = $this->ensureUtf8($time->time_schedule);
                    $numberBooking = $time->number_booking ?? 0;
                    $timeAvailable = $maxNumber - $numberBooking;

                    if ($dateCarbon->isToday()) {
                        if (strpos($timeSchedule, '-') !== false) {
                            [$startStr, $endStr] = explode('-', $timeSchedule);
                            $slotEnd = Carbon::parse($schedule->date_schedule . ' ' . trim($endStr));

                            if ($slotEnd->lte($now)) {
                                continue;
                            }
                        }
                    }

                    if ($timeAvailable > 0) {
                        $timeDetails[] = "• {$timeSchedule} (còn {$timeAvailable} chỗ)";
                    }
                }

                if (!empty($timeDetails)) {
                    $timeList   = implode("\n  ", $timeDetails);
                    $response[] = "👨‍⚕️ <strong>Bác sĩ {$doctorDisplayName}</strong>\n  {$timeList}";
                }
            }

            return empty($response) ? "Không còn lịch trống." : implode("\n\n", $response);
        } catch (\Exception $e) {
            Log::error('Lỗi trong getAvailableSlots: ' . $e->getMessage(), [
                'doctorName' => $doctorName,
                'date'       => $date,
                'service'    => $service,
                'exception'  => $e
            ]);
            return "Có lỗi khi tìm lịch khám. Vui lòng thử lại.";
        }
    }

    private function getDoctorAvailability($doctorName, $date)
    {
        try {
            $doctorName = $this->ensureUtf8($doctorName);

            $now       = Carbon::now();
            $endOfWeek = $now->copy()->endOfWeek();

            $doctor = User::where('name', 'like', '%' . $doctorName . '%')
                ->where('type', User::TYPE_DOCTOR)
                ->first();

            if (!$doctor) {
                return "Không tìm thấy bác sĩ $doctorName trong hệ thống.";
            }

            $schedules = Schedule::with(['doctor', 'times' => function ($q) {
                $q->where('status', 0);
            }])
                ->where('doctor_id', $doctor->id)
                ->whereBetween('date_schedule', [$now->toDateString(), $endOfWeek->toDateString()])
                ->where('status', 1)
                ->orderBy('date_schedule')
                ->get();

            if ($schedules->isEmpty()) {
                return "Không, bác sĩ $doctorName không còn lịch làm việc từ bây giờ đến hết tuần.";
            }

            $response  = "Có, bác sĩ $doctorName còn lịch làm việc từ bây giờ đến hết tuần:\n\n";
            $hasSlots  = false;

            foreach ($schedules as $schedule) {
                $dayCarbon = Carbon::parse($schedule->date_schedule);
                $day       = $dayCarbon->format('d/m/Y');
                $dayName   = $dayCarbon->locale('vi')->dayName;
                $maxNumber = $schedule->max_number;

                $timeDetails          = [];
                $totalAvailableFuture = 0;

                foreach ($schedule->times as $time) {
                    $timeSchedule = $this->ensureUtf8($time->time_schedule);

                    if (strpos($timeSchedule, '-') === false) {
                        continue;
                    }

                    [$startStr, $endStr] = explode('-', $timeSchedule);
                    $slotStart = Carbon::parse($schedule->date_schedule . ' ' . trim($startStr));
                    $slotEnd   = Carbon::parse($schedule->date_schedule . ' ' . trim($endStr));

                    if ($slotEnd->lte($now)) {
                        continue;
                    }

                    $numberBooking = $time->number_booking ?? 0;
                    $timeAvailable = $maxNumber - $numberBooking;

                    if ($timeAvailable > 0) {
                        $timeDetails[]          = "• {$timeSchedule} (còn {$timeAvailable} chỗ)";
                        $totalAvailableFuture  += $timeAvailable;
                    } else {
                        $timeDetails[] = "• {$timeSchedule} (hết chỗ)";
                    }
                }

                if (empty($timeDetails) || $totalAvailableFuture <= 0) {
                    continue;
                }

                $hasSlots   = true;
                $timeList   = implode("\n  ", $timeDetails);
                $statusText = "✅ Còn {$totalAvailableFuture} chỗ trống";

                $response .= "📅 <strong>{$dayName}, {$day}</strong> - {$statusText}\n  {$timeList}\n\n";
            }

            if (!$hasSlots) {
                return "Không còn lịch khám trống của bác sĩ $doctorName từ thời điểm hiện tại đến hết tuần.";
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Lỗi trong getDoctorAvailability: ' . $e->getMessage(), [
                'doctorName' => $doctorName,
                'date'       => $date,
                'exception'  => $e
            ]);
            return "Có lỗi khi kiểm tra lịch làm việc. Vui lòng thử lại.";
        }
    }

    private function getDoctorsByService($service)
    {
        $service = $this->ensureUtf8($service);
        return User::whereHas('specialties', function ($q) use ($service) {
            $q->where('specialties.name', 'like', '%' . $service . '%');
        })
            ->where('type', User::TYPE_DOCTOR)
            ->select('users.id', 'users.name')
            ->get();
    }

    private function extractTimesFromSlots($slots)
    {
        $lines = explode("\n", $slots);
        $times = [];
        foreach ($lines as $line) {
            if (strpos($line, 'Bác sĩ') !== false) {
                $parts = explode(':', $line);
                if (count($parts) > 1) {
                    $timePart = trim($parts[1]);
                    if (!empty($timePart) && $timePart !== '' && $timePart !== ' ') {
                        $times[] = $timePart;
                    }
                }
            }
        }
        return implode(', ', $times);
    }

    private function hasAvailableTimes($slots)
    {
        $times = $this->extractTimesFromSlots($slots);
        return !empty($times) && trim($times) !== '';
    }

    private function findBestMatch($message)
    {
        $knowledge = BotKnowledge::all();
        $matches = [];

        $messageWords = explode(' ', mb_strtolower($message, 'UTF-8'));

        foreach ($knowledge as $item) {
            $questionWords = explode(' ', mb_strtolower($item->question, 'UTF-8'));

            $matchingWords = array_intersect($messageWords, $questionWords);

            if (!empty($matchingWords)) {
                $wordSimilarity = count($matchingWords) / max(count($messageWords), count($questionWords));
                $levenshteinSimilarity = $this->calculateSimilarity($message, $item->question);
                $similarity = ($wordSimilarity * 0.6) + ($levenshteinSimilarity * 0.4);

                if ($similarity > 0.55) {
                    $matches[] = [
                        'answer' => $item->answer,
                        'confidence' => $item->confidence * $similarity,
                        'question' => $item->question
                    ];
                }
            }
        }

        usort($matches, function ($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });

        return $matches;
    }

    private function calculateSimilarity($str1, $str2)
    {
        $str1 = $this->ensureUtf8(preg_replace('/\s+/', ' ', trim(mb_strtolower($str1, 'UTF-8'))));
        $str2 = $this->ensureUtf8(preg_replace('/\s+/', ' ', trim(mb_strtolower($str2, 'UTF-8'))));

        if ($str1 === $str2) {
            return 1.0;
        }

        $levenshtein = levenshtein($str1, $str2);
        $maxLength = max(mb_strlen($str1, 'UTF-8'), mb_strlen($str2, 'UTF-8'));
        $levSimilarity = $maxLength ? 1 - ($levenshtein / $maxLength) : 0;

        $words1 = array_filter(explode(' ', $str1), function ($word) {
            return mb_strlen($word, 'UTF-8') > 2;
        });
        $words2 = array_filter(explode(' ', $str2), function ($word) {
            return mb_strlen($word, 'UTF-8') > 2;
        });
        $commonWords = array_intersect($words1, $words2);
        $wordSimilarity = count($words1) ? count($commonWords) / count($words1) : 0;

        $similarity = 0.7 * $levSimilarity + 0.3 * $wordSimilarity;

        if (mb_strlen($str1, 'UTF-8') <= 10 && $similarity > 0.8) {
            $similarity *= 0.9;
        }
        // Thêm penalty nếu str1 là subset của str2 nhưng str2 dài hơn nhiều
        $lenRatio = mb_strlen($str1, 'UTF-8') / max(mb_strlen($str2, 'UTF-8'), 1);
        if ($lenRatio < 0.6) {
            $similarity *= $lenRatio;
        }

        return min(max($similarity, 0), 1);
    }

    private function getDefaultResponse($message)
    {
        $responses = [
            'giờ làm việc' => 'Phòng khám làm việc từ 8:00 đến 17:00, thứ 2 đến thứ 7. Chủ nhật nghỉ.',
            'đặt lịch' => 'Bạn có thể đặt lịch khám qua chatbot này. Vui lòng cung cấp tên bác sĩ, ngày, hoặc chuyên khoa bạn muốn khám.',
            'bác sĩ' => 'Phòng khám có đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm. Bạn muốn biết về bác sĩ nào hoặc dịch vụ nào?',
            'giá khám' => 'Giá khám dao động từ 200.000đ đến 500.000đ tùy dịch vụ. Bạn muốn biết giá dịch vụ cụ thể nào?',
            'bảo hiểm' => 'Chúng tôi chấp nhận thanh toán qua bảo hiểm y tế cho các dịch vụ được chi trả.',
            'địa chỉ' => 'Phòng khám tọa lạc tại số 123, đường ABC, quận XYZ, TP.HCM.',
            'bãi đậu xe' => 'Có, phòng khám có bãi đậu xe miễn phí cho khách hàng.',
            'số điện thoại' => 'Bạn có thể liên hệ qua số điện thoại: 0123-456-789 hoặc hotline: 0987-654-321.',
        ];

        foreach ($responses as $keyword => $response) {
            if (mb_strpos($message, $keyword, 0, 'UTF-8') !== false) {
                return $this->ensureUtf8($response);
            }
        }

        $specialties = Specialty::pluck('name')->map(function ($name) {
            return $this->ensureUtf8($name);
        })->implode(', ');
        return $this->ensureUtf8("Xin chào! Tôi có thể giúp bạn:\n\n• 🗓️ Đặt lịch khám — VD: 'Đặt lịch khám răng'\n• 👨‍⚕️ Tìm bác sĩ — VD: 'Tìm bác sĩ khoa Nội'\n• 💰 Xem giá khám — VD: 'Giá khám Tim mạch'\n• 📋 Tra cứu lịch hẹn — VD: 'Tra cứu BK000001'\n\nBạn cần hỗ trợ gì ạ?");
    }

    private function ensureUtf8($string)
    {
        if (!is_string($string) || empty($string)) {
            Log::debug('Chuỗi rỗng hoặc không phải chuỗi trong ensureUtf8', ['string' => $string]);
            return '';
        }

        if (mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }

        $cleaned = @iconv('UTF-8', 'UTF-8//IGNORE', $string);
        if ($cleaned !== false && !empty($cleaned)) {
            return $cleaned;
        }

        $converted = @mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));
        if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
            return $converted;
        }

        $fallback = preg_replace('/[^\p{L}\p{N}\p{P}\p{Zs}]/u', '', $string);
        if (!empty($fallback) && mb_check_encoding($fallback, 'UTF-8')) {
            return $fallback;
        }

        Log::warning('Không thể làm sạch chuỗi trong ensureUtf8', ['string' => $string]);
        return '';
    }
    private function callGeminiAPI($message, $context)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        // Lấy thông tin thực từ database để cung cấp cho AI
        $specialties = Specialty::pluck('name')->implode(', ');
        $doctors = User::where('type', User::TYPE_DOCTOR)
            ->select('name', 'price_min', 'price_max')
            ->get()
            ->map(fn($d) => "{$d->name} (giá: " . number_format($d->price_min) . "đ)")
            ->implode(', ');

        $systemPrompt = "Bạn là trợ lý tư vấn y tế của Bệnh viện Đa khoa Phương Đông, tại Số 9 Phố Viên, Đông Ngạc, Hà Nội. Hotline: 19001806.

    Thông tin bệnh viện:
    - Giờ làm việc: Thứ 2-6: 6:30-21:45, Thứ 7-CN: 8:30-19:45
    - Các chuyên khoa: {$specialties}
    - Đội ngũ bác sĩ: {$doctors}

    Nhiệm vụ của bạn:
    1. Tư vấn chuyên khoa phù hợp dựa trên triệu chứng bệnh nhân mô tả
    2. Hỗ trợ đặt lịch khám (hỏi tên bác sĩ hoặc chuyên khoa, ngày giờ)
    3. Cung cấp thông tin về giá khám, bác sĩ, lịch trống
    4. Trả lời các câu hỏi về bệnh viện

    Quy tắc:
    - Luôn trả lời bằng tiếng Việt, thân thiện và chuyên nghiệp
    - Không chẩn đoán bệnh, chỉ gợi ý khoa khám phù hgetDefaultResponseợp
    - Nếu bệnh nhân mô tả triệu chứng nguy hiểm (đau ngực dữ dội, khó thở nặng...), khuyên đến cấp cứu ngay
    - Câu trả lời ngắn gọn, rõ ràng, không quá 200 từ
    - Nếu người dùng muốn đặt lịch, hỏi thêm thông tin cần thiết
    - Phân biệt chính xác vị trí cơ thể: 'đau đầu' (headache) khác hoàn toàn với 'đau đầu gối' (knee pain), không được nhầm lẫn
    - Khi người dùng nói 'đau đầu', chỉ tư vấn Khoa Thần kinh hoặc Khoa Nội, KHÔNG tư vấn về khớp hay cơ xương
    - Khi người dùng nói 'đau đầu gối' hoặc 'đau khớp', mới tư vấn Khoa Cơ xương khớp
    ";

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]]
            ],
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $message]]]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 500,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            Log::error('Gemini API error', ['code' => $httpCode, 'response' => $response]);
            return null;
        }

        $data = json_decode($response, true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }
}
