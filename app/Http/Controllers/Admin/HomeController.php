<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\Booking;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        view()->share([
            'home_active' => 'active',
            'book_for' => User::BOOK_FOR,
            'status' => Booking::STATUS,
            'class_status' => Booking::CLASS_STATUS,
            'gender' => User::GENDERS,
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $clinic = Clinic::select('id')->count();
        $specialty = Specialty::select('id')->count();
        $currentTime = Carbon::now()->format('Y-m-d');
        $bookings = Booking::with(['patient'])->where('date_booking', $currentTime);
        $patient = User::where('type', User::TYPE_PATIENT)->count();
        $schedule = Schedule::where('date_schedule', $currentTime);

        if (!$user->hasRole(['super-admin'])) {
            $bookings->where('doctor_id', $user->id);
            $schedule->where('doctor_id', $user->id);
        }
        $bookings = $bookings->whereIn('status', [2, 3, 4, 5, 7])->get();

        $schedule = $schedule->get();

        $doctor = User::where('type', User::TYPE_DOCTOR)->count();

        // ===== THÊM MỚI: lọc năm =====
        $selectedYear = request('year', 'all');
        $bookingByClinicQuery = Booking::join('users', 'bookings.doctor_id', '=', 'users.id')
            ->selectRaw('users.name as ten_khoa, COUNT(bookings.id) as so_luong');
        if ($selectedYear !== 'all') {
            $bookingByClinicQuery->whereYear('bookings.created_at', $selectedYear);
        }
        $bookingByClinic = $bookingByClinicQuery->groupBy('users.id', 'users.name')->get();
        // ===== KẾT THÚC THÊM MỚI =====

        $viewData = [
            'clinic' => $clinic,
            'specialty' => $specialty,
            'bookings' => $bookings,
            'user' => $user,
            'patient' => $patient,
            'currentTime' => $currentTime,
            'schedule' => $schedule,
            'doctor' => $doctor,
            'bookingByClinic' => $bookingByClinic,
            'selectedYear' => $selectedYear, // thêm mới
        ];
        return view('admin.home.index', $viewData);
    }
}
