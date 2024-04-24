<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Auth;
use View;
use App\Transformers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Validator;
use App\User;
use Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(){
        return view('auth.passwords.reset');
    }

    public function createReset(Request $request){
        $validator           = Validator::make($request->all(),[
            'code'           => 'required',
            'password'       => 'required|alpha_dash|confirmed|between:6,50',
            'password_confirmation'  => 'required|alpha_dash|between:6,50'
        ]);

        if($validator->passes()){

            if($user = User::where('phone','=',request('phone'))->first()){
                if($user->code != request('code')){
                    $msg        =   'كود التحقق غير صحيح .';
                    return back()->with('msg',$msg);
                }
                $user->password = Hash::make(request('password'));
                $user->save();
                $msg        =    'تم تحديث كلمة المرور بنجاح';
                return redirect('login')->with('successmsg',$msg);
            }else{
                return back()->with('msg','رقم الهاتف الذى ادخلتة غير صحيح.');
            }

        }else{
                return back()->with('errors',$validator->errors());
        }
    }

    //  public function reset(Request $request)
    // {
    //     $this->validate($request, $this->rules(), $this->validationErrorMessages());
    //     // Here we will attempt to reset the user's password. If it is successful we
    //     // will update the password on an actual user model and persist it to the
    //     // database. Otherwise we will parse the error and return the response.
    //     $response = $this->broker()->reset(
    //         $this->credentials($request), function ($user, $password)
    //         {
    //             $this->resetPassword($user, $password);
    //         }
    //     );
        
        
    //     if ($request->wantsJson())
    //     {
    //         if ($response == Password::PASSWORD_RESET)
    //         {
    //             return response()->json(Json::response(null, trans('passwords.reset')));
    //         } else
    //         {
    //             return response()->json(Json::response($request->input('email'), trans($response), 202));
    //         }
    //     }
        
        
    //     // If the password was successfully reset, we will redirect the user back to
    //     // the application's home authenticated view. If there is an error we can
    //     // redirect them back to where they came from with their error message.
    //     return $response == Password::PASSWORD_RESET
    //     ? $this->sendResetResponse($response)
    //     : $this->sendResetFailedResponse($request, $response);
    // }
}
