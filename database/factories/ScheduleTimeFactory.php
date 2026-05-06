<?php

namespace Database\Factories;

use App\Models\ScheduleTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleTimeFactory extends Factory
{
    protected $model = ScheduleTime::class;

    public function definition()
    {
        return [
            'schedule_id' => 1,
            'time_schedule' => $this->faker->time('H:i'),
        ];
    }
}
