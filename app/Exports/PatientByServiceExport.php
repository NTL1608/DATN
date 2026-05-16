<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\User;
use App\Models\Booking;
use App\Models\Specialty;
use Carbon\Carbon;

class PatientByServiceExport implements FromView
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
        $specialtyId = $data['specialty_id'];
        $specialty = Specialty::find($specialtyId);
        $data['specialty'] = $specialty->name;

        $patients = Booking::with(['doctor' => function ($query) {
            $query->with(['clinic', 'specialty']);
        }, 'patient'])->where('specialty_id', $specialtyId);

        if (!empty($data['status'])) {
            $patients->where('status', $data['status']);
        }

        $patients = $patients->whereBetween('created_at', [$start_date, $end_date])->get();
        return view('exports.specialty', compact('patients', 'data', 'genders', 'status'));
    }
}