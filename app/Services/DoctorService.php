<?php

namespace App\Services;
use App\Models\Locations;
use App\Models\User;
use Carbon\Carbon;

class DoctorService
{

    public function doctorInfo($id)
    {
        $doctor = User::with(['specialties', 'schedule' => function($query) {

            $currentTime = Carbon::now();
            $timeCheck = Carbon::now()->setTime(7, 30);
            $check = $timeCheck->diffInHours($currentTime, false);

            if ($check < 0) {
                $dateSchedule = $currentTime->format('Y-m-d');
            } else {
                $dateSchedule =  $currentTime->addDays(1)->format('Y-m-d');
            }

            $query->with(['times' => function ($q) {
                $q->where('status', 0);
            }])->where('status', 1)->where('date_schedule', '>=', $dateSchedule)->orderBy('date_schedule', 'ASC')->limit(7);

        }, 'clinic', 'city', 'specialty', 'district', 'street']);

        $doctor = $doctor->whereIn('type', [User::TYPE_DOCTOR])->where('id', $id)->first();

        return $doctor;
    }

    public function listDoctors($request, $condition = [])
    {

        $doctors = User::with(['schedule' => function($query) use ($request) {
            $currentTime = Carbon::now();
            $timeCheck = Carbon::now()->setTime(7, 30);
            $check = $timeCheck->diffInHours($currentTime, false);

            if ($check < 0) {
                $dateSchedule = $currentTime->format('Y-m-d');
            } else {
                $dateSchedule =  $currentTime->addDays(1)->format('Y-m-d');
            }

            $query->with(['times' => function ($q) {
                $q->where('status', 0);
            }])->where('status', 1)->where('date_schedule', '>=', $dateSchedule)->orderBy('date_schedule', 'ASC')->limit(7);

        }, 'clinic', 'city', 'ratings'])->where('status', 1);

        if ($request->city) {
            $location = Locations::where('loc_slug', $request->city)->first();

            if ($location) {
                $doctors->where('city_id', $location->id);
            }
        }
        if ($request->keyword) {
            $doctors->where('name', 'like','%' .$request->keyword . '%');
        }

        if ($request->clinic) {
            $doctors->where('clinic_id', $request->clinic);
        }

        if ($request->specialty) {
            $doctors->where('specialty_id', $request->specialty);
        }

        if (!empty($condition)) {
            $doctors->where($condition);
        }

        $doctors = $doctors->whereIn('type', [User::TYPE_DOCTOR])->paginate(NUMBER_PAGINATION);

        return $doctors;
    }

    public function listUsers($id)
    {
        return User::whereIn('type', [User::TYPE_DOCTOR, User::TYPE_ADMIN])->where(['clinic_id' => $id, 'status' => 1])->orderBy('job_title')->get()->groupBy('job_title');
    }
}
