<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use App\SystemUser;
use Auth;
use Hash;

class SystemUserAuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct(Request $request)
    {
        // $this->middleware('guest:system_users', ['except' => ['logout']]);
    }

    protected function guard()
    {
        return \Auth::guard('system_users');
    }

    /**
     * Show the application’s login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.system.login');
    }

     /**
     * Show the application’s login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.system.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:system_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return SystemUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'active' => false
        ]);
    }

    public function logout()
    {
        Auth::guard('system_users')->logout();

        return redirect('/');
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $errors = implode(',', $validator->messages()->all());

            return redirect()->route('system.register.form')->withInput()->with('flash_error', $errors);
        }

        $user = $this->create($request->all());

        return redirect(route('system.login.form'));
    }

    protected function authenticated()
    {
        try {
            return redirect()->route('/home')->with('flash_success', 'login_success');
        } catch (\Throwable $th) {
            return redirect()->route('system.login.form')->with('error', $th->getMessage());
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:5'
         ]);

        // Attempt to log the user in
        if (\Auth::guard('system_users')->attempt(['email' => $request->email, 'password' => $request->password, 'active' => true], $request->remember)) {
            return redirect()->intended(route('show_ranking'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->with('flash_error', 'username_password_not_match');
    }
}
