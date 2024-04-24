<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\externalAppTokens;
use Auth;

class ExternalAppTokensController extends Controller{

    public function externalApps(){
        $externalApps = externalAppTokens::latest()->get();
        return view('dashboard.externalApps.index',compact('externalApps'));
    }

    #add coupon
    public function createExternalApp(Request $request){
        $this->validate($request,[
            'email'       => 'required|email',
            'phone'       => 'required',
            'password'    => 'required',
            'client_name' => 'required',
            'app_name'    => 'required',
        ]);
        $number         = convert2english(request('phone'));
        $phone          = phoneValidate($number);

        $externalApp = new externalAppTokens();
        $externalApp->email       = $request->email;
        $externalApp->phone       = $phone;
        $externalApp->password    = bcrypt($request->password);
        $externalApp->client_name = $request->client_name;
        $externalApp->app_name    = $request->app_name;
        $externalApp->app_id      = create_rand_numbers(12);
        $externalApp->server_key  = create_random_secret_key($request->email);
        $externalApp->save();
        History(Auth::user()->id,'بأضافة تطبيق جديد');
        Session::flash('success','تم اضافة التطبيق بنجاح');
        return back();
    }

    #update coupon
    public function updateExternalApp(Request $request){
        $this->validate($request,[
            'id'       => 'required',
            'edit_email'       => 'required|email',
            'edit_phone'       => 'required',
            'edit_client_name' => 'required',
            'edit_app_name'    => 'required',
        ]);
        $number         = convert2english(request('edit_phone'));
        $phone          = phoneValidate($number);

        $externalApp = externalAppTokens::find($request->id);
        $externalApp->email       = $request->edit_email;
        $externalApp->phone       = $phone;
        if($request->edit_password){
            $externalApp->password    = bcrypt($request->edit_password);
        }
        $externalApp->client_name = $request->edit_client_name;
        $externalApp->app_name    = $request->edit_app_name;
        $externalApp->save();
        History(Auth::user()->id,'تعديل بيانات التطبيق الخارجي');
        Session::flash('success','تم تعديل التطبيق الخارجي بنجاح');
        return back();

    }

    #delete user
    public function DeleteExternalApp(Request $request){
            $externalApp = externalAppTokens::findOrFail($request->id);
            History(Auth::user()->id,'بحذف الكوبون '.$externalApp->app_name);
            $externalApp->delete();
            return back()->with('success','تم الحذف');
    }


}
