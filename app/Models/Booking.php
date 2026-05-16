<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\MailHelper;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = [
        'booking_code',
        'schedule_time_id',
        'doctor_id',
        'patient_id',
        'specialty_id',
        'name',
        'email',
        'phone',
        'birthday',
        'book_for',
        'city_id',
        'district_id',
        'street_id',
        'address',
        'reason_other',
        'date_booking',
        'time_booking',
        'time_type',
        'price',
        'status',
        'file_result',
        'note',
        'instruction',
        'number',
        'citizen_id_number',
        'insurance_card_number',
        'qr_code',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;

    const STATUS = [
        1 => 'Tiếp nhận',
        2 => 'KH xác nhận',
        3 => 'Đã thanh toán',
        4 => 'Đã khám',
        5 => 'Đã trả kết quả',
        6 => 'Đã hủy',
        7 => 'NV xác nhận'
    ];

    const CLASS_STATUS = [
        1 => 'btn-secondary',
        2 => 'btn-info',
        3 => 'btn-success',
        4 => 'btn-warning',
        5 => 'bg-teal',
        6 => 'btn-danger',
        7 => 'bg-maroon'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
    }

    /**
     * @param $request
     * @param string $id
     */
    public function createOrUpdate($request, $id = '')
    {
        $params = $request->except(['_token', 'file_result', 'submit']);

        if ($id) {
            $booking = $this->with(['doctor', 'patient'])->find($id);
            $oldStatus = $booking->status;

            if ($request->file_result) {
                $fileName = \Illuminate\Support\Str::slug($booking->patient->name);
                $image = upload_image('file_result', 'file-result', [], $fileName);
                if ($image['code'] == 1) {
                    $params['file_result'] = $image['name'];
                }
            }

            $booking->fill($params)->save();

            $schedule = ScheduleTime::with(['schedule.doctor.specialties'])
                ->where('id', $booking->schedule_time_id)->first();

            $numberBooking = self::where('doctor_id', $booking->doctor_id)
                ->where('schedule_time_id', $booking->schedule_time_id)
                ->where('date_booking', $schedule->schedule->date_schedule)
                ->whereIn('status', [2,3,4,5,7])
                ->count();

            if ($oldStatus == 1 && isset($params['status']) && in_array($params['status'], [2,3,4,5,7])) {

                $schedule->number_booking = $numberBooking;

                if ($numberBooking >= $schedule->schedule->max_number) {
                    $schedule->status = 1;
                }
                $schedule->save();
            }

            if ($oldStatus != 1 && isset($params['status']) && ($params['status'] == 6 || $params['status'] == 1)) {

                if ($numberBooking < $schedule->schedule->max_number) {
                    $schedule->status = 0;
                }
                $schedule->number_booking = $numberBooking;
                $schedule->save();
            }

            if ($params['status'] == 7 && $booking->status !== 7) {
                $dataMail = [
                    'id' => $id,
                    'name' => $booking->name,
                    'booking_code' => $booking->booking_code,
                    'specialty' => isset($booking->specialty) ? $booking->specialty->name : '',
                    'name_doctor' => isset($booking->doctor) ? $booking->doctor->name : '',
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
            return $booking;
        } else {
            return $this->fill($params)->save();
        }
    }
}
