<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Specialty;
use App\Http\Requests\PatientByDepartmentRequest;
use App\Http\Requests\PatientByServiceRequest;
use App\Http\Requests\PatientByDoctorRequest;
use App\Exports\PatientByDepartmentExport;
use App\Exports\PatientByServiceExport;
use App\Exports\PatientByDoctorExport;

class BookingReportStatisticController extends Controller
{
    //
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        view()->share([
            'booking_report_active' => 'active',
            'status' => Booking::STATUS,
        ]);
    }
    //
    public function statistics()
    {
        $clinics = Clinic::all();
        $specialties = Specialty::all();
        $users = User::whereIn('type', [User::TYPE_DOCTOR, User::TYPE_ADMIN])->get();
        return view('admin.booking.statistics', compact('clinics', 'specialties', 'users'));
    }

    public function reportClinic(PatientByDepartmentRequest $request)
    {
        $data = $request->except('_token');

        ob_end_clean();
        try {
            $name = 'bao-cao-khoa-kham-benh-';
            return \Excel::download(new PatientByDepartmentExport($data), $name . \Carbon\Carbon::now() .'.xlsx');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error','Export data thất bại!');
        }
    }

    public function reportService(PatientByServiceRequest $request)
    {
        $data = $request->except('_token');

        ob_end_clean();
        try {
            $name = 'bao-cao-dich-vu-kham-benh-';
            return \Excel::download(new PatientByServiceExport($data), $name . \Carbon\Carbon::now() .'.xlsx');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error','Export data thất bại!');
        }
    }

    public function reportDoctor(PatientByDoctorRequest $request)
    {
        $data = $request->except('_token');

        ob_end_clean();
        try {
            $name = 'bao-cao-bac-si-kham-benh-';
            return \Excel::download(new PatientByDoctorExport($data), $name . \Carbon\Carbon::now() .'.xlsx');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error','Export data thất bại!');
        }
    }
}
