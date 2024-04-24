<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use View;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\User;
use Validator;
use Illuminate\Http\Request;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)    {
        $validator                     = Validator::make($request->all(),[
            'phone'                    => 'required'
        ]);

        if($validator->passes())
        {
            $user            = User::where('phone', request('phone'))->first();
            $msg             = 'كود التفعيل الخاص بك من '.setting('site_title').' : ';
            if( $user ) {
                $user->code = generate_code();
                $user->save();
                $msg        = $msg . $user->code;
                $phone      = $user->phone;

                send_mobile_sms('00966'.$phone, $msg);

                $data = [
                    'id'    => $user->id,
                    'code'  => $user->code
                ];
                return redirect('password/reset')->with('successmsg','تم أرسال كود التفعيل بنجاح');
            }else{
                $msg  = 'لا يوجد حساب بهذا الرقم';
                return back()->with('msg',$msg);
            }
        }else{
            return back()->with('errors', $validator->errors());
        }
    }


    // public function getResetToken(Request $request)
    // {
    //    $validator= Validator::make($request->all(), ['email' => 'required|email'],['email.required'=>'يجب ملء حقل البريد الالكترونى','email.email'=>'يجب ادخال بريد الكترونى صحيح']);
    //     if ($request->wantsJson())
    //     {
    //         if($validator->fails())
    //          {
    //             return response()->json(['status'=>'0','message'=>$validator->errors()->all()]);
    //          }
             
    //         $user = User::where('email', $request->input('email'))->first();
    //         if (!$user)
    //         {
    //             return response()->json(['status'=>'0','message'=>'Email Not Found']);
    //         }
    //         $token = $this->broker()->createToken($user);
    //         return response()->json(['status'=>'1','token' => $token]);
    //     }
    // }
}
