<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\Schedule;
use Hash;

class CheckDateSchedule implements Rule
{
    public $request;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        //
        $this->request = $request;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $doctor_id = $this->request->doctor_id;

        $date_schedule = $this->request->date_schedule;
        if (!$doctor_id) {
            return false;
        }
        $schedule = Schedule::where(['doctor_id' => $doctor_id, 'date_schedule' => $date_schedule])->first();

        if ($schedule) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Lịch làm việc đã được đăng ký';
    }
}
