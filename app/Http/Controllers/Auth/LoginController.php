<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = 'home/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
      return 'phone';
    }

    public function Adminloginform(){
        // Auth::loginUsingId(1);
        return view('dashboard.login');
    }

    public function Ambassadorloginform(){
        return view('dashboard.Ambassadorloginform');
    }

    public function Adminlogin(Request $request){
        $credentials = $request->only('email', 'password');
        // $remember = ($request->get('remember'))? true: false;
        if(Auth::attempt($credentials)) {
            if(Auth::user()->role > 0){
               return redirect()->intended('admin/dashboard');
            }else{
               Auth::logout();
               return back()->with('error','البريد الالكتروني أو كلمة المرور غير صحيحة .');   
            }
        }else{
            return back()->with('error','البريد الالكتروني أو كلمة المرور غير صحيحة .');  
        }  
    }

    public function removeAccountForm(){
        return view('removeAccountForm');
    }

    public function removeAccount(Request $request){

        $number         = convert2english($request->phone);
        $phone          = phoneValidate($number);
        if(Auth::attempt(['phone' => $phone, 'password' => $request->password])) {
            Auth::user()->delete();
            return back()->with('successmsg','تم حذف الحساب بنجاح');   
        }else{
            return back()->with('error',' رقم الهاتف أو كلمة المرور غير صحيحة .');  
        }  
    }

    public function LoginForm(){
        $this->data['site_logo'] = setting('site_logo');
        return view('auth.login',$this->data);
    }

    public function authenticate(Request $request){
        $credentials = $request->only('phone', 'password');
        // $remember = ($request->get('remember'))? true: false;
        if(Auth::attempt($credentials, true)) {
            return redirect()->intended('/');
        }else{
            return back()->with('error','رقم الهاتف أو كلمة المرور غير صحيحة .');  
        }
    }

    public function apisloginform(){
        return view('apis_dashboard.login');
    }

    public function apisLogin(Request $request){
        $credentials = $request->only('email', 'password');
        if(Auth::guard('externalAppTokens')->attempt($credentials)) {
               return redirect()->intended('apis/apisIndex');
        }else{
            return back()->with('error','البريد الالكتروني أو كلمة المرور غير صحيحة .');  
        }  
    }

    public function apisLogout(Request $request){
        Auth::logout();
        return redirect()->route('apislogin');
    }  

    public function logout(Request $request){
        Auth::logout();
        return redirect('admin/login');
    }


}
