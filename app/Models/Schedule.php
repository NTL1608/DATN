<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $fillable = ['current_number', 'max_number', 'date_schedule', 'time_schedule', 'time_type', 'jump', 'status',  'doctor_id', 'created_at', 'updated_at'];
    public $timestamps = true;

    const DATE_SCHEDULE = [
        1 => 'Hôm nay',
        2 => 'Ngày mai',
        3 => 'Ngày kia',
    ];
    const JUMPS = [
        15 => '15 phút',
        30 => '30 phút',
        45 => '45 phút',
    ];

    const LIST_TIMES = [
        15 => [
            '7:00-7:15' => '7:00-7:15',
            '7:15-7:30' => '7:15-7:30',
            '7:30-7:45' => '7:30-7:45',
            '7:45-8:00' => '7:45-8:00',
            '8:00-8:15' => '8:00-8:15',
            '8:15-8:30' => '8:15-8:30',
            '8:30-8:45' => '8:30-8:45',
            '8:45-9:00' => '8:45-9:00',
            '9:00-9:15' => '9:00-9:15',
            '9:15-9:30' => '9:15-9:30',
            '9:30-9:45' => '9:30-9:45',
            '9:45-10:00' => '9:45-10:00',
            '10:00-10:15' => '10:00-10:15',
            '10:15-10:30' => '10:15-10:30',
            '10:30-10:45' => '10:30-10:45',
            '10:45-11:00' => '10:45-11:00',
            '11:00-11:15' => '11:00-11:15',
            '11:15-11:30' => '11:15-11:30',
            '11:30-11:45' => '11:30-11:45',
            '11:45-12:00' => '11:45-12:00',
            '13:30-13:45' => '13:30-13:45',
            '13:45-14:00' => '13:45-14:00',
            '14:00-14:15' => '14:00-14:15',
            '14:15-14:30' => '14:15-14:30',
            '14:30-14:45' => '14:30-14:45',
            '14:45-15:00' => '14:45-15:00',
            '15:00-15:15' => '15:00-15:15',
            '15:15-15:30' => '15:15-15:30',
            '15:30-15:45' => '15:30-15:45',
            '15:45-16:00' => '15:45-16:00',
            '16:00-16:15' => '16:00-16:15',
            '16:15-16:30' => '16:15-16:30',
            '16:30-16:45' => '16:30-16:45',
            '16:45-17:00' => '16:45-17:00',
        ],
        30 => [
            '7:00-7:30' => '7:00-7:30',
            '7:30-8:00' => '7:30-8:00',
            '8:00-8:30' => '8:00-8:30',
            '8:30-9:00' => '8:30-9:00',
            '9:00-9:30' => '9:00-9:30',
            '9:30-10:00' => '9:30-10:00',
            '10:00-10:30' => '10:00-10:30',
            '10:30-11:00' => '10:30-11:00',
            '11:00-11:30' => '11:00-11:30',
            '11:30-12:00' => '11:30-12:00',
            '13:30-14:00' => '13:30-14:00',
            '14:00-14:30' => '14:00-14:30',
            '14:30-15:00' => '14:30-15:00',
            '15:00-15:30' => '15:00-15:30',
            '15:30-16:00' => '15:30-16:00',
            '16:00-16:30' => '16:00-16:30',
            '16:30-17:00' => '16:30-17:00',
        ],
        45 => [
            '7:00-7:45' => '7:00-7:45',
            '7:45-8:30' => '7:45-8:30',
            '8:30-9:15' => '8:30-9:15',
            '9:15-10:00' => '9:15-10:00',
            '10:00-10:45' => '10:00-10:45',
            '10:45-11:30' => '10:45-11:30',
            '11:30-12:00' => '11:30-12:00',
            '13:30-14:15' => '13:30-14:15',
            '14:15-15:00' => '14:15-15:00',
            '15:00-15:45' => '15:00-15:45',
            '15:45-16:15' => '15:45-16:15',
            '16:15-17:00' => '16:15-17:00',
        ]
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function times()
    {
        return $this->hasMany(ScheduleTime::class, 'schedule_id' , 'id');
    }

    /**
     * @param $request
     * @param string $id
     */
    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['_token', 'list_times', 'submit']);
        $time_type = intval($request->time_type);

//        switch ($time_type) {
//            case 1 :
//                $params['date_schedule'] = Carbon::now()->format('Y-m-d');
//                break;
//            case 2 :
//                $params['date_schedule'] = Carbon::now()->addDays(1)->format('Y-m-d');
//                break;
//            case 3 :
//                $params['date_schedule'] = Carbon::now()->addDays(2)->format('Y-m-d');
//                break;
//            default:
//                $params['date_schedule'] = Carbon::now()->format('Y-m-d');
//                break;
//        }

        if ($id) {
            $schedule = $this->find($id);
            ScheduleTime::where('schedule_id', $id)->delete();
            $schedule->update($params);
        } else {
            $schedule = new Schedule();
            $schedule->fill($params)->save();
        }
        if ($schedule) {
            $listTimes = $request->list_times;
            if (!empty($listTimes)) {

                foreach ($listTimes as $time) {
                    $data = [
                        'schedule_id' => $schedule->id,
                        'time_schedule' => $time,
                    ];
                    ScheduleTime::insert($data);
                }
            };

        }
    }
}
