<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Specialty;
use App\Http\Requests\ResultBookingRequest;
use App\Helpers\MailHelper;

class BookingController extends Controller
{
    private $booking;
    //
    /**
     * HomeController constructor.
     */
    public function __construct(Booking $booking, Clinic $clinic, Specialty $specialty)
    {
        view()->share([
            'booking_active' => 'active',
            'book_for' => User::BOOK_FOR,
            'gender' => User::GENDERS,
            'status' => Booking::STATUS,
            'class_status' => Booking::CLASS_STATUS,
            'jobTitle' => User::JOB_TITLE,
            'clinics' => $clinic->all(),
            'specialties' => $specialty->all(),
        ]);
        $this->booking = $booking;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = Auth::user();

        $bookings = Booking::with(['doctor' => function ($query) {
            $query->with(['clinic', 'specialty']);
        }, 'patient']);

        if (!$user->hasRole(['super-admin'])) {
            $bookings->where('doctor_id', $user->id)->where('status', '!=', 1);
        }
        if ($request->user_code || $request->name || $request->clinic_id) {
            if ($request->user_code || $request->name) {
                $bookings->whereIn('patient_id', function ($query) use ($request) {

                    if ($request->user_code) {
                        $query->select('id')->from('users')->where('user_code', $request->user_code);
                    }

                    if ($request->name) {
                        $query->select('id')->from('users')->where('name', 'like', '%' . $request->name . "%");
                    }
                });
            }
            if ($request->clinic_id || $request->name) {
                $bookings->orWhereIn('doctor_id', function ($query) use ($request) {
                    if ($request->name) {
                        $query->select('id')->from('users')->where('name', 'like', '%' . $request->name . "%");
                    }
                    if ($request->clinic_id) {
                        $query->select('id')->from('users')->where('clinic_id', $request->clinic_id);
                    }
                });
            }
        }

        if ($request->citizen_id_number) {
            $bookings->where('citizen_id_number', $request->citizen_id_number);
        }

        if ($request->insurance_card_number) {
            $bookings->where('insurance_card_number', $request->insurance_card_number);
        }

        if ($request->status) {
            $bookings->where('status', $request->status);
        }

        if ($request->to_date_booking) {
            $bookings->where('date_booking', '>=', $request->to_date_booking);
        }

        if ($request->from_date_booking) {
            $bookings->where('date_booking', '<=', $request->from_date_booking);
        }
        if ($request->specialty_id) {
            $bookings->whereHas('doctor', function ($query) use ($request) {
                $query->whereHas('specialties', function ($query) use ($request) {
                    $query->where('specialties.id', $request->specialty_id);
                });
            });
        }

        $bookings = $bookings->orderByDesc('id')->paginate(NUMBER_PAGINATION);

        $viewData = [
            "bookings" => $bookings,
            'user' => $user
        ];

        return view('admin.booking.index', $viewData);
    }

    public function edit($id)
    {
        $booking = Booking::with(['doctor' => function ($query) {
            $query->with(['clinic', 'specialty']);
        }, 'patient'])->find($id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('admin.booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            $this->booking->createOrUpdate($request, $id);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function resultBooking(ResultBookingRequest $request, $id)
    {
        \DB::beginTransaction();
        try {
            $this->booking->createOrUpdate($request, $id);

            $booking = Booking::with(['doctor', 'patient'])->find($id);
            $dataMail = [
                'id' => $id,
                'name' => $booking->name,
                'booking_code' => $booking->booking_code,
                'name_doctor' => isset($booking->doctor) ? $booking->doctor->name : '',
                'email' => $booking->email,
                'file_result' => $booking->file_result,
                'note' => $booking->note,
                'instruction' => $booking->instruction,
                'status' => 'Đã trả kết quả khám',
                'confirm' => false,
            ];

            MailHelper::sendMailSuccess($dataMail);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function delete($id)
    {
        //
        $booking = Booking::find($id);
        if (!$booking) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            $booking->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }


    public function show($id)
    {
        $booking = Booking::with(['doctor' => function ($query) {
            $query->with(['clinic', 'specialty']);
        }, 'patient', 'specialty'])->find($id);


        if (!$booking) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('admin.booking.medical_exam_form', compact('booking'));
    }

    public function resultPrint($id)
    {
        $booking = Booking::with(['doctor' => function ($query) {
            $query->with(['clinic', 'specialty']);
        }, 'patient', 'specialty'])->find($id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('admin.booking.result_print', compact('booking'));
    }
}
