<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Booking;
use App\Models\User;
use App\Http\Requests\RatingRequest;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    //

    public function rating(RatingRequest $request, $id)
    {
        $user= Auth::guard('users')->user();


        if (!$user) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để có thể đánh giá bác sĩ.');
        }

        $booking = Booking::where(['doctor_id' => $id, 'patient_id' => $user->id])->count();

        if ($booking == 0) {
            return redirect()->back()->with('error', 'Bạn chưa sử dụng dịch vụ không thể đánh giá bác sĩ.');
        }

        $numberRating = Rating::where(['doctor_id' => $id, 'patient_id' => $user->id])->count();

        if ($numberRating > 0 && !isset($request->rating_id)) {
            return redirect()->back()->with('error', 'Bạn đã thực hiện đánh giá bacs sĩ');
        }

        $doctor = User::find($id);

        \DB::beginTransaction();
        try {
            $params = $request->except('_token', 'rating_id');
            $params['doctor_id'] = $id;
            $params['patient_id'] = $user->id;
            $params['status'] = 1;

            if ($request->rating_id) {
                $rating = Rating::find($request->rating_id);
                $rating->update($params);
            } else {
                Rating::create($params);
            }

            \DB::commit();

            if ($doctor) {
                return redirect()->route('doctor.detail', ['id' => $doctor->id, 'slug' => safeTitle($doctor->name), 'tab'=>'rating']);
            }

            return redirect()->back()->with('success', 'Đánh giá bác sĩ thành công');

        } catch (\Exception $exception) {

            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã sảy ra lỗi không thể đánh giá bác sĩ');
        }
    }
}
