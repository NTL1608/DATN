<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleTime extends Model
{
    use HasFactory;
    protected $table = 'schedule_times';
    protected $fillable = ['schedule_id', 'time_schedule', 'number_booking', 'status', 'created_at', 'updated_at'];
    public $timestamps = true;

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
}
