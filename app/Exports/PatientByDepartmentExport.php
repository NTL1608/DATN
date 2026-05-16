<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\User;
use App\Models\Booking;
use App\Models\Clinic;
use Carbon\Carbon;

class PatientByDepartmentExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $data = $this->data;
        $genders = User::GENDERS;
        $status = Booking::STATUS;
        $start_date = Carbon::parse($data['start_date'])->startOfDay();
        $end_date = Carbon::parse($data['end_date'])->startOfDay();
        $clinicId = $data['clinic_id'];
        $clinic = Clinic::find($clinicId);
        $data['clinic'] = $clinic->name;
        $docterIds = User::where('clinic_id', $clinicId)->pluck('id')->toArray();
        $patients = null;

        if (!empty($docterIds)) {
            $patients = Booking::with(['doctor' => function ($query) {
                $query->with(['clinic', 'specialty']);
            }, 'patient'])->whereIn('doctor_id', $docterIds);


            if (!empty($data['status'])) {
                $patients->where('status', $data['status']);
            }
            $patients = $patients->whereBetween('created_at', [$start_date, $end_date])->get();
        }
        return view('exports.clinic', compact('patients', 'data', 'genders', 'status'));
    }
}