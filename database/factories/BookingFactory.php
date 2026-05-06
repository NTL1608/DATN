<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Specialty;
use App\Models\ScheduleTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'schedule_time_id' => ScheduleTime::factory(),
            'phone' => $this->faker->phoneNumber(),
            'doctor_id' => User::factory()->state(['type' => User::TYPE_DOCTOR]),
            'patient_id' => User::factory()->state(['type' => User::TYPE_PATIENT]),
            'date_booking' => $this->faker->date(),
            'number' => $this->faker->numberBetween(1, 100),
            'email' => $this->faker->safeEmail(),
            'name' => $this->faker->name(),
            'specialty_id' => Specialty::factory(),
            'status' => 1,
        ];
    }
}
