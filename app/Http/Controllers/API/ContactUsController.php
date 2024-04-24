<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Contact;
use App\User;
use App\Social;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class ContactUsController extends Controller{

    public function contactways(Request $request){
            $data['email']  = setting('site_email');
            $data['phone']  = setting('site_phone');
            return response()->json(successReturn($data));
    } 

    public function createContact(Request $request){
        $validator         = Validator::make($request->all(),[
            'message'      => 'required|min:3',
        ]);

        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $contact = new Contact();
            $contact->user_id         = $user->id;
            $contact->message         = $request->message;
            $contact->name            = $user->name;
            $contact->email           = $user->email;
            $contact->phone           = $user->phone;
            if($request->hasFile('image')) {
                $image           = $request->file('image');
                $name            = md5($request->file('image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/img/complaint');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $contact->image    = $name;
            }            
            $contact->save();
            $msg = trans('contactus.sent_success');
            return response()->json(successReturnMsg($msg));
        }else{
                $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function socials(Request $request){
        $socials = Social::orderBy('created_at','DESC')->get(); 
        $data = [];
        foreach ($socials as $social) {
           $data[] = ['name' => ($social->name)? $social->name:'',
                      'link' => ($social->link)? $social->link:'',
                      'logo' => ($social->logo)? url('dashboard/uploads/socialicon/'.$social->logo) : ''
                     ];
        }       
        return response()->json(successReturn($data));
    } 

}
