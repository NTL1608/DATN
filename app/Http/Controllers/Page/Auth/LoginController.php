<?php

namespace App\Http\Controllers\Page\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //$this->middleware('guest')->except('logout');
        $this->user = $user;
    }

    public function login()
    {
        if (\Auth::guard('users')->check()) {
            return redirect()->back();
        }

        return view('page.auth.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $data = $request->except('_token');
        $identifier = $data['email']; // Có thể là email, phone hoặc citizen_id_number
        $password = $data['password'];

        // Tìm user theo email, phone hoặc citizen_id_number
        $user = $this->user->findByEmailOrPhoneOrCitizenId($identifier);

        if (!$user) {
            return redirect()->back()->with('error', 'Thông tin tài khoản không tồn tại');
        }

        if ($user->status == User::LOCK) {
            return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa');
        }

        // Verify password thủ công vì Auth::attempt() chỉ hoạt động với email
        if (Hash::check($password, $user->password)) {
            Auth::guard('users')->login($user, $request->has('remember'));
            return redirect()->route('user.home.index');
        }

        return redirect()->back()->with('error', 'Mật khẩu không chính xác.');
    }

    public function logout()
    {
        Auth::guard('users')->logout();
        return redirect()->route('user.home.index');
    }
}
