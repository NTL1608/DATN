<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\ClinicSpecialty;
use App\Http\Requests\ClinicRequest;


class ClinicController extends Controller
{
    protected $clinic;
    //
    /**
     * HomeController constructor.
     */
    public function __construct(Clinic $clinic)
    {
        $this->clinic = $clinic;

        view()->share([
            'clinic_active' => 'active',
            'specialties' => Specialty::all()
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $clinics = Clinic::orderByDesc('id');
        if ($request->name) {
            $clinics->where('name', 'like', '%'.$request->name.'%');
        }
        $clinics = $clinics->paginate(NUMBER_PAGINATION);
        return view('admin.clinic.index', compact('clinics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.clinic.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClinicRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $this->clinic->createOrUpdate($request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function show($id)
    {
        $clinic = Clinic::with('specialties')->find($id);

        if (!$clinic) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        return view('admin.clinic.show', compact('clinic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $clinic = Clinic::with('specialties')->find($id);
        $activeSpecialtyIds = ClinicSpecialty::where('clinic_id', $id)->pluck('specialty_id')->toArray();

        if (!$clinic) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        return view('admin.clinic.edit', compact('clinic', 'activeSpecialtyIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClinicRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $this->clinic->createOrUpdate($request, $id);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $clinic = Clinic::find($id);
        if (!$clinic) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        try {
            $clinic->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }

    public function loadSpecialty(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->clinic_id;

            $clinic = Clinic::with('specialties')->find($id);

            if (!$clinic) {
                return response([
                    'code' => 404,
                    'message' => 'Đã xảy ra lỗi'
                ]);
            }

            $specialties = $clinic->specialties;

            $html = view("admin.common.specialty", compact('specialties'))->render();

            return response([
                'code' => 200,
                'html' => $html,
                'message' => 'Lấy dữ liệu thành công'
            ]);
        }
    }
}
