<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Locations;
use App\Models\Clinic;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $user;

    public function __construct(Role $role, Locations $locations, Clinic $clinic, Specialty $specialty, User $user)
    {
        view()->share([
            'user_active' => 'active',
            'roles' => $role->all(),
            'status' => User::STATUS,
            'types' => User::TYPES,
            'genders' => User::GENDERS,
            'positions' => User::POSITIONS,
            'jobTitle' => User::JOB_TITLE,
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
        $users = User::whereIn('type', [User::TYPE_DOCTOR, User::TYPE_ADMIN])->with([
            'userRole' => function ($userRole) {
                $userRole->select('*');
            },
            'clinic',
            'specialties'
        ]);

        if ($request->user_code) {
            $users->where('user_code', $request->user_code);
        }

        if ($request->status) {
            $users->where('status', $request->status);
        }

        if ($request->name) {
            $users->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->clinic_id) {
            $users->where('clinic_id', $request->clinic_id);
        }
        if ($request->specialty_id) {
            $users->whereHas('specialties', function ($query) use ($request) {
                $query->where('specialties.id', $request->specialty_id);
            });
        }

        $users = $users->orderBy('id', 'DESC')->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
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
            'userRole' => function ($userRole) {
                $userRole->select('*');
            },
            'specialties'
        ])->find($id);

        $listRoleUser = \DB::table('role_user')->where('user_id', $id)->first();
        if (!$user) {
            return redirect()->route('get.list.user')->with('danger', 'Quyền không tồn tại');
        }

        $clinic = Clinic::with('specialties')->find($user->clinic_id);

        $specialties = $clinic->specialties;

        $arraySpecialty = $user->specialties->pluck('id')->toArray();

        $viewData = [
            'user' => $user,
            'listRoleUser' => $listRoleUser,
            'specialties' => $specialties,
            'arraySpecialty' => $arraySpecialty,
        ];
        return view('admin.user.edit', $viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
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
            return redirect()->back()->with('success', 'Đã xóa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function show(Request $request)
    {
        if (Auth::check()) {
            $id = Auth::id();
        }
        if ($request->user_id) {
            $id = $request->user_id;
        }

        if (!isset($id)) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $user = User::with([
            'userRole' => function ($userRole) {
                $userRole->select('*');
            },
            'specialties'
        ])->find($id);

        $listRoleUser = \DB::table('role_user')->where('user_id', $id)->first();
        if (!$user) {
            return redirect()->route('get.list.user')->with('danger', 'Quyền không tồn tại');
        }

        $clinic = Clinic::with('specialties')->find($user->clinic_id);

        $specialties = isset($clinic->specialties) ? $clinic->specialties : [];

        $arraySpecialty = $user->specialties->pluck('id')->toArray();

        $viewData = [
            'user' => $user,
            'listRoleUser' => $listRoleUser,
            'specialties' => $specialties,
            'arraySpecialty' => $arraySpecialty,
            'user_profile_active' => 'active',
            'user_active' => ''
        ];

        return view('admin.user.detail', $viewData);
    }
}
