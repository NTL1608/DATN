<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use App\Services\DoctorService;

class ClinicController extends Controller
{
    protected $clinic;
    protected $doctorService;
    //
    /**
     * HomeController constructor.
     */
    public function __construct(Clinic $clinic, DoctorService $doctorService)
    {
        $this->clinic = $clinic;
        $this->doctorService = $doctorService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $clinics = Clinic::orderByDesc('id')->paginate(NUMBER_PAGINATION);
        return view('page.clinic.index', compact('clinics'));
    }

    public function detail(Request $request, $id)
    {
        $clinic = Clinic::find($id);
        $positions = User::POSITIONS_TS;
        $jobTitle = User::JOB_TITLE;
        if (!$clinic) {
            return redirect()->back()->with('error', 'Dữ liệu khoa khám bệnh không tồn tại');
        }
        //        $condition = [
        //            'clinic_id' => $id
        //        ];
        //        $doctors = $this->doctorService->listDoctors($request, $condition);

        $users = $this->doctorService->listUsers($id);

        return view('page.clinic.detail', compact('clinic', 'users', 'positions', 'jobTitle'));
    }

    public function loadSpecialty(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->clinic_id;

            $clinic = Clinic::with('specialties')->find($id);

            if (!$clinic) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Đã xảy ra lỗi'
                ]);
            }

            $specialties = $clinic->specialties;

            return response()->json([
                'code' => 200,
                'specialties' => $specialties,
                'message' => 'Lấy dữ liệu thành công'
            ]);
        }
    }
}
