<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingAppointmentRequest;
use App\Models\Booking;
use App\Models\ScheduleTime;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Locations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MailHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{

    //
    public function booking($id)
    {
        $positions = User::POSITIONS;
        $positionTs = User::POSITIONS_TS;
        $genders = User::GENDERS;
        $book_for = User::BOOK_FOR;

        $schedule = ScheduleTime::with(['schedule' => function ($query) {
            $query->with(['doctor' => function ($doctor) {
                $doctor->with(['specialties', 'clinic']);
            }]);
        }])->where('id', $id)->first();

        if (!$schedule) {
            return redirect()->route('user.home.index')->with('error', 'Thông tin lịch khám không tồn tại');
        }

        $numberBooking = Booking::where('schedule_time_id', $id)->whereIn('status', [1, 2, 3, 4, 5, 7])->count();

        if ($numberBooking >= $schedule->schedule->max_number) {
            return redirect()->back()->with('error', 'Số người đăng ký khám trong giờ đã vượt mức cho phép');
        }

        $citys  = Locations::getCity();
        $district = Locations::getDistrict();
        $street = Locations::getStreet();

        $viewData = [
            'schedule' => $schedule,
            'positions' => $positions,
            'genders' => $genders,
            'book_for' => $book_for,
            'citys' => $citys,
            'district' => $district,
            'street' => $street,
            'positionTs' => $positionTs,
        ];
        if (Auth::guard('users')->check()) {
            $user = Auth::guard('users')->user();
            $viewData['user'] = $user;
        }

        return view('page.booking.index', $viewData);
    }

    public function bookingAppointment(BookingAppointmentRequest $request, $id)
    {
        $params = $request->except(['_token', 'submit']);

        $booking = Booking::where(['schedule_time_id' => $id, 'phone' => $request->phone])
            ->whereIn('status', [1, 2, 3, 4, 5, 7])
            ->first();

        if ($booking) {
            return redirect()->route('user.home.index')->with('error', 'Bạn đã đặt lịch khám vào thời điểm hiện tại');
        }

        $schedule = ScheduleTime::with(['schedule.doctor.specialties'])
            ->where('id', $id)->first();

        if (!$schedule) {
            return redirect()->route('user.home.index')->with('error', 'Lịch khám đã full hoặc lịch khám không tồn tại.');
        }

        $params['doctor_id'] = isset($schedule->schedule) ? $schedule->schedule->doctor_id : '';

        $numberBooking = Booking::where('doctor_id', $params['doctor_id'])
            ->whereIn('status', [2, 3, 4, 5, 7])
            ->where('date_booking', $schedule->schedule->date_schedule)
            ->count();

        if ($numberBooking) {
            $numberBooking = $numberBooking + 1;
        } else {
            $numberBooking = 1;
        }

        if (Auth::guard('users')->check()) {
            $params['patient_id'] = Auth::guard('users')->id();
        }

        $specialty = Specialty::find($request->specialty_id);

        $params['schedule_time_id'] = $id;
        $params['number'] = $numberBooking;
        $params['date_booking'] = isset($schedule->schedule) ? $schedule->schedule->date_schedule : null;
        $params['time_booking'] = isset($schedule) ? $schedule->time_schedule : null;
        $params['price'] = isset($schedule->schedule->doctor) ? $schedule->schedule->doctor->price_min : null;
        $params['created_at'] = Carbon::now();
        $params['updated_at'] = Carbon::now();

        DB::beginTransaction();
        try {
            $id = Booking::insertGetId($params);

            Booking::find($id)->update([
                'booking_code' => 'PK' . str_pad($id, 6, '0', STR_PAD_LEFT),
            ]);

            $dataMail = [
                'id' => $id,
                'name' => $params['name'],
                'booking_code' => 'PK' . str_pad($id, 6, '0', STR_PAD_LEFT),
                'specialty' => $specialty->name,
                'number' => $numberBooking,
                'name_doctor' => isset($schedule->schedule->doctor) ? $schedule->schedule->doctor->name : '',
                'email' => $params['email'],
                'date_booking' => $params['date_booking'],
                'time_booking' => $params['time_booking'],
                'price' => $params['price'],
                'status' => 'Chờ xác nhận',
                'confirm' => true,
            ];

            
            MailHelper::sendMail($dataMail);
            
            DB::commit();
            return redirect()->route('user.home.index')
                ->with('success', 'Đăng ký lịch khám thành công chúng tôi sẽ liên hệ để xác nhận lích khám.'); // Dùng URL thay vì base64
        } catch (\Exception $exception) {
            Log::error('Booking error: ' . $exception->getMessage(), [
                'exception' => $exception,
                'params' => $params,
                'schedule' => isset($schedule) ? $schedule->toArray() : null
            ]);
            DB::rollBack();
            return redirect()->route('user.home.index')->with('error', 'Đã xảy ra lỗi không thể đặt lịch khám');
        }
    }

    public function confirm($id)
    {
        $booking = Booking::with(['doctor', 'patient', 'specialty'])->find($id);

        if (!$booking) {
            return redirect()->route('user.home.index')->with('error', 'Lịch khám không tồn tại');
        }

        DB::beginTransaction();
        try {
            $booking->status = 2;
            $booking->save();

            $schedule = ScheduleTime::with(['schedule.doctor.specialties'])
                ->where('status', 0)
                ->where('id', $booking->schedule_time_id)->first();

            if (!$schedule) {
                return redirect()->route('user.home.index')->with('error', 'Bác sĩ đã hết lịch khám vui lòng liên hệ chăm sóc khách hàng');
            }

            $numberBooking = Booking::where('doctor_id', $booking->doctor_id)
                ->where('schedule_time_id', $booking->schedule_time_id)
                ->where('date_booking', $schedule->schedule->date_schedule)
                ->whereIn('status', [2])
                ->count();

            $schedule->number_booking = $numberBooking;

            if ($numberBooking >= $schedule->schedule->max_number) {
                $schedule->status = 1;
            }
            $schedule->save();

            // Lấy QR code đã tồn tại hoặc tạo mới nếu chưa có
            $urlQrCode = route('booking.print.medical.exam.form', $id);
            $qrCodeFileName = 'qrcode-booking-' . $id . '.svg';
            $qrCodeDir = public_path('uploads/qrcodes');
            $qrCodeFilePath = $qrCodeDir . '/' . $qrCodeFileName;
            
            // Nếu file chưa tồn tại, tạo mới
            if (!file_exists($qrCodeFilePath)) {
                if (!file_exists($qrCodeDir)) {
                    mkdir($qrCodeDir, 0755, true);
                }
                
                $qrCodeSvg = QrCode::format('svg')
                    ->size(400)
                    ->errorCorrection('H')
                    ->generate($urlQrCode);
                
                // Thêm logo vào center
                $logoPath = public_path('page/img/logo-4.png');
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoSvg = '<image x="35%" y="35%" width="30%" height="30%" href="data:image/png;base64,' . $logoData . '"/>';
                    $qrCodeSvg = str_replace('</svg>', $logoSvg . '</svg>', $qrCodeSvg);
                }
                
                file_put_contents($qrCodeFilePath, $qrCodeSvg);
            }
            
            $qrCodeUrl = url('uploads/qrcodes/' . $qrCodeFileName);
            
            // Thêm QR code vào data mail và gửi
            $dataMail = [
                'id' => $id,
                'name' => $booking->name,
                'booking_code' => $booking->booking_code,
                'name_doctor' => isset($booking->doctor) ? $booking->doctor->name : '',
                'email' => $booking->email,
                'date_booking' => $booking->date_booking,
                'time_booking' => $booking->time_booking,
                'price' => $booking->price,
                'number' => $booking->number,
                'specialty' => isset($booking->specialty) ? $booking->specialty->name : '',
                'status' => 'Lịch khám của bạn đã được đăng ký thành công',
                'confirm' => false,
                'qr_code' => $qrCodeUrl, // URL tuyệt đối của file QR code
                'qr_code_file' => $qrCodeFilePath, // File path để attach
            ];
            MailHelper::sendMail($dataMail);

            DB::commit();
            return redirect()->route('user.home.index')
                ->with('success', 'Xác nhận lịch khám thành công. ')
                ->with('qrCode', $qrCodeUrl);
        } catch (\Exception $exception) {
            return redirect()->route('user.home.index')->with('error', 'Đã xảy ra lỗi không thể đặt lịch khám');
        }
    }

    public function printMedicalExamForm($id)
    {
        $jobTitle = User::JOB_TITLE;
        $positions = User::POSITIONS;
        $gender = User::GENDERS;
        $status = Booking::STATUS;
        $booking = Booking::with(['doctor' => function ($query) {
            $query->with(['clinic', 'specialty']);
        }, 'patient', 'specialty'])->find($id);


        if (!$booking) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('page.booking.medical_exam_form', compact('booking', 'jobTitle', 'positions', 'gender', 'status'));
        
    }
}
