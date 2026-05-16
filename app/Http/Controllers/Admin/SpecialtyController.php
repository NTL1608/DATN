<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialtyRequest;
use App\Models\Specialty;

class SpecialtyController extends Controller
{
    protected $specialty;
    //
    /**
     * HomeController constructor.
     */
    public function __construct(Specialty $specialty)
    {
        view()->share([
            'specialty_active' => 'active',
        ]);
        $this->specialty = $specialty;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $specialties = Specialty::orderByDesc('id');

        if ($request->name) {
            $specialties->where('name', 'like', '%'.$request->name.'%');
        }
        $specialties = $specialties->paginate(NUMBER_PAGINATION);

        return view('admin.specialty.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.specialty.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecialtyRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $this->specialty->createOrUpdate($request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
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
        $specialty = Specialty::find($id);

        if (!$specialty) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        return view('admin.specialty.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SpecialtyRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $this->specialty->createOrUpdate($request, $id);
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
        $specialty = Specialty::find($id);
        if (!$specialty) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        try {
            $specialty->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }
}
