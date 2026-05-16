<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Locations;
use App\Models\Clinic;
use App\Models\Specialty;

class PatientController extends Controller
{
    //
    protected $user;

    public function __construct(Role $role, Locations $locations, Clinic $clinic, Specialty $specialty, User $user)
    {
        view()->share([
            'patient_active' => 'active',
            'roles' => $role->all(),
            'status' => User::STATUS,
            'genders' => User::GENDERS,
            'positions' => User::POSITIONS,
            'clinics' => $clinic->all(),
            'specialties' => $specialty->all(),
            'citys'  => $locations->getCity(),
            'district'  => $locations->getDistrict(),
            'street'  => $locations->getStreet(),
        ]);
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $users = User::where('type', User::TYPE_PATIENT);
        if ($request->user_code) {
            $users->where('user_code', $request->user_code);
        }
        if ($request->status) {
            $users->where('status', $request->status);
        }
        if ($request->name) {
            $users->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->citizen_id_number) {
            $users->where('citizen_id_number', $request->citizen_id_number);
        }

        if ($request->insurance_card_number) {
            $users->where('insurance_card_number', $request->insurance_card_number);
        }
        $users = $users->orderBy('id', 'DESC')->paginate(10);
        return view('admin.patient.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.patient.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $this->user->createOrUpdate($request);
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
        $user = User::with([
            'userRole' => function($userRole)
            {
                $userRole->select('*');
            }
        ])->find($id);
        $listRoleUser = \DB::table('role_user')->where('user_id', $id)->first();
        if(!$user) {
            return redirect()->route('get.patient.user')->with('danger', 'Quyền không tồn tại');
        }

        $viewData = [
            'user' => $user,
            'listRoleUser' => $listRoleUser
        ];
        return view('admin.patient.create', $viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $this->user->createOrUpdate($request, $id);
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
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        \DB::beginTransaction();
        try {
            $user->delete();
            \DB::commit();
            return redirect()->back()->with('success','Đã xóa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }
}
