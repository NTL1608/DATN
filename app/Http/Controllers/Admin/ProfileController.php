<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use App\Models\Role;
use App\Models\Locations;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Http\Requests\ChangePasswordAdminRequest;

class ProfileController extends Controller
{
    protected $user;

    public function __construct(Role $role, Locations $locations, Clinic $clinic, Specialty $specialty, User $user)
    {
        view()->share([
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
    public function index()
    {
        //
        $agency = Auth::user();
        $user = User::with([
            'userRole' => function($userRole)
            {
                $userRole->select('*');
            },
            'specialties'
        ])->find($agency->id);

        $listRoleUser = \DB::table('role_user')->where('user_id', $agency->id)->first();
        if(!$user) {
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
        ];

        return view('admin.user.profile', $viewData);
    }

    public function update(ProfileRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $data = $request->except('_token', 'images');
            if (isset($request->images) && !empty($request->images)) {
                $image = upload_image('images');
                if ($image['code'] == 1)
                    $data['avatar'] = $image['name'];
            }
            User::find($id)->update($data);
            \DB::commit();
            return redirect()->route('profile.index')->with('success','Chỉnh sửa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->route('profile.index')->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function changePassword()
    {
        view()->share([
            'change_password' => 'active',
        ]);
        return view('admin.user.change_password');
    }

    public function postChangePassword(ChangePasswordAdminRequest $request)
    {
        $data['password'] = bcrypt($request->password);

        try {
            User::find(Auth::id())->update($data);
            Auth::logout();
            return redirect()->route('admin.login');
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', '[Error ]' . $exception->getMessage());
        }
    }
}
