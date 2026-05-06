<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateInfoAccountRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Models\Booking;

class AccountController extends Controller
{
    //
    public function infoAccount()
    {
        $genders = User::GENDERS;
        $user= Auth::guard('users')->user();
        return view('page.auth.account', compact('user', 'genders'));
    }

    public function updateInfoAccount(UpdateInfoAccountRequest $request, $id)
    {
        \DB::beginTransaction();
        try {
            $params = $request->except('_token', 'password', 'password_confirm');
            $user =  User::find(Auth::guard('users')->user()->id);
            $user->fill($params)->save();
            \DB::commit();
            return redirect()->back()->with('success', 'Cập nhật thành công.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể cập nhật tài khoản');
        }
    }

    public function bookings()
    {
        if (!Auth::guard('users')->check()) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể truy cập tính năng');
        }
        $status = Booking::STATUS;
        $class_status = Booking::CLASS_STATUS;
        $positionTs = User::POSITIONS_TS;

        $userId =  Auth::guard('users')->user()->id;
        $bookings = Booking::with(['doctor' =>function ($query) {
            $query->with(['clinic', 'specialty']);
        }])->where('patient_id', $userId)->paginate(NUMBER_PAGINATION);

        return view('page.auth.booking', compact('bookings', 'status', 'class_status', 'positionTs'));
    }

    public function changePassword()
    {
        return view('page.auth.change_password');
    }

    public function postChangePassword(ChangePasswordRequest $request)
    {
        \DB::beginTransaction();
        try {
            $user =  User::find(Auth::guard('users')->user()->id);
            $user->password = bcrypt($request->password);
            $user->save();
            \DB::commit();
            Auth::guard('users')->logout();
            return redirect()->route('page.user.account')->with('success', 'Đổi mật khẩu thành công.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể đổi mật khẩu');
        }
    }

    public function cancelBooking($id)
    {
        $booking = Booking::find($id);

        if(!$booking) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể hủy đặt lịch');
        }
        $booking->status = 6;

        try {
            $booking->save();
            return redirect()->back()->with('success', 'Hủy đặt lịch thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể hủy đặt lịch');
        }
    }
}
