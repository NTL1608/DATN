<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\ScheduleTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CopySchedule extends Command
{
    protected $signature = 'schedule:copy-from-yesterday';
    protected $description = 'Tự động copy lịch làm việc từ hôm qua sang hôm nay';

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // Kiểm tra hôm nay đã có lịch chưa
        $existingToday = Schedule::where('date_schedule', $today)->count();
        if ($existingToday > 0) {
            $this->info("Hôm nay ($today) đã có lịch rồi, bỏ qua.");
            Log::info("CopySchedule: Hôm nay đã có lịch, bỏ qua.");
            return;
        }

        // Lấy lịch của hôm qua
        $yesterdaySchedules = Schedule::with('times')
            ->where('date_schedule', $yesterday)
            ->get();

        if ($yesterdaySchedules->isEmpty()) {
            $this->warn("Hôm qua ($yesterday) không có lịch nào để copy.");
            Log::warning("CopySchedule: Hôm qua không có lịch.");
            return;
        }

        $count = 0;
        foreach ($yesterdaySchedules as $schedule) {
            // Tạo lịch mới cho hôm nay
            $newSchedule = Schedule::create([
                'doctor_id'      => $schedule->doctor_id,
                'date_schedule'  => $today,
                'time_type'      => $schedule->time_type,
                'max_number'     => $schedule->max_number,
                'current_number' => 0,
                'jump'           => $schedule->jump,
                'status'         => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Copy các khung giờ
            foreach ($schedule->times as $time) {
                ScheduleTime::create([
                    'schedule_id'    => $newSchedule->id,
                    'time_schedule'  => $time->time_schedule,
                    'number_booking' => 0,
                    'status'         => 0,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            $count++;
        }

        $this->info("Đã copy $count lịch từ $yesterday sang $today thành công!");
        Log::info("CopySchedule: Đã copy $count lịch từ $yesterday sang $today.");
    }
}
