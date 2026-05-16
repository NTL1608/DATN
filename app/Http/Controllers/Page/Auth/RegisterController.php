<?php

namespace App\Http\Controllers\Page\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }
    public function register()
    {
        if (\Auth::guard('users')->check()) {
            return redirect()->back();
        }
        $genders = User::GENDERS;
        return view('page.auth.register', compact('genders'));
    }

    public function postRegister(RegisterRequest $request)
    {
        \DB::beginTransaction();
        try {
            $params = $request->except('_token', 'password', 'password_confirm');
            $params['password'] = bcrypt($request->password);
            $params['type'] = User::TYPE_PATIENT;

            $user = new User();
            $user->fill($params)->save();

            $user->update([
                'user_code' => 'BN'.str_pad($user->id, 6, '0', STR_PAD_LEFT)
            ]);

            Auth::guard('users')->loginUsingId($user->id, true);

            \DB::commit();
            return redirect()->route('user.home.index');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể đăng ký tài khoản');
        }
    }
}
