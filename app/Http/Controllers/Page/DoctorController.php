<?php

namespace App\Http\Controllers\Page;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DoctorService;
use App\Http\Controllers\Controller;
use App\Models\Rating;

class DoctorController extends Controller
{
    protected $doctorService;
    //

    /**
     * HomeController constructor.
     */
    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function doctorInfo(Request $request, $id)
    {
        $ratingEdit = null;
        $positions = User::POSITIONS;
        $positionTs = User::POSITIONS_TS;
        $jobTitle = User::JOB_TITLE;
        $doctor = $this->doctorService->doctorInfo($id);
        if (!$doctor) {
            return redirect()->back()->with('error', 'Thông tin bác sĩ không tồn tại');
        }
        $ratings = Rating::with('patient');

        if ($request->star) {
            $ratings->where('star', $request->star);
        }

        $ratings = $ratings->where('doctor_id', $id)->orderByDesc('id')->paginate(20);
        $star = Rating::where('doctor_id', $id)->sum('star');
        $count = Rating::where('doctor_id', $id)->count();

        $stars = Rating::selectRaw(\DB::raw('COUNT(star) as number_star, star'))->where('doctor_id', $id)->groupBy('star')->get();

        $numberStar = 0;
        if ($star > 0) {
            $numberStar = $star/ $count;
            $numberStar = round($numberStar, 1);
        }

        if ($request->rating_id) {
            $ratingEdit = Rating::find($request->rating_id);
        }
        return view('page.doctor.index', compact('doctor', 'positions', 'ratings', 'ratingEdit', 'stars', 'numberStar', 'jobTitle', 'positionTs'));
    }

    public function search(Request $request)
    {
        $doctors = $this->doctorService->listDoctors($request);
        return view('page.doctor.search', compact('doctors'));
    }
}
