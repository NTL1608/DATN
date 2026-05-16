<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    //
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        view()->share([
            'rating_active' => 'active',
        ]);
    }

    public function index(Request $request)
    {
        $ratings = Rating::with(['patient', 'doctor'])->orderByDesc('id')->paginate(NUMBER_PAGINATION);

        return view('admin.rating.index', compact('ratings'));
    }

    public function delete($id)
    {
        $rating = Rating::find($id);
        if (!$rating) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        \DB::beginTransaction();
        try {
            $rating->delete();
            \DB::commit();
            return redirect()->back()->with('success','Đã xóa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }
}
