<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Services\DoctorService;
use App\Models\User;

class SpecialtyController extends Controller
{
    protected $specialty;
    protected $doctorService;
    //
    /**
     * HomeController constructor.
     */
    public function __construct(Specialty $specialty, DoctorService $doctorService)
    {
        $this->specialty = $specialty;
        $this->doctorService = $doctorService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $specialties = Specialty::query();
        if ($request->keyword) {
            $specialties->where('name', 'like', '%'.$request->keyword.'%');
        }
        $specialties = $specialties->orderByDesc('id')->paginate(NUMBER_PAGINATION);
        return view('page.specialty.index', compact('specialties'));
    }


    public function detail(Request $request, $id)
    {
        $specialty = Specialty::find($id);
        $positions = User::POSITIONS_TS;
        $jobTitle = User::JOB_TITLE;
        if (!$specialty) {
            return redirect()->back()->with('error', 'Dữ liệu dịch vụ không tồn tại');
        }

//        $condition = [
//            'specialty_id' => $id
//        ];
//        $doctors = $this->doctorService->listDoctors($request, $condition);
        $userIds = \DB::table('user_specialties')->where('specialty_id', $id)->pluck('user_id')->toArray();
        $users = User::whereIn('type', [User::TYPE_DOCTOR, User::TYPE_ADMIN])
            ->whereIn('id', $userIds)
            ->where(['status' => 1])
            ->orderBy('job_title')
            ->get()
            ->groupBy('job_title');

        return view('page.specialty.detail', compact('specialty', 'users', 'positions', 'jobTitle'));
    }
}
