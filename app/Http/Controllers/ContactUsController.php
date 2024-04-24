<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Mail\PublicMessage;
use Mail;
use App\SmsEmailNotification;
use Session;
use App\User;
use App\userDevices;
use App\userMeta;

class ContactUsController extends Controller
{

    #inbox page
    public function InboxPage(){
        $messages = Contact::latest()->get();
        return view('dashboard.inbox.inbox',compact('messages',$messages));
    }

    #show message
    public function ShowMessage($id){
        $message = Contact::findOrFail($id);
        $message->ShowOrNow = 1;
        $message->update();
        return view('dashboard.inbox.show_message',compact('message',$message));
    }

    #send SMS
    public function SMS(Request $request){
        $this->validate($request,[
            'phone'       =>'required',
            'sms_message' =>'required|min:5'
        ]);
        if($request->has('msgid')){
            if( $contact = Contact::find($request->msgid) ){
                $contact->answer .= ' '.$request->sms_message;
                $contact->answer_at = date('Y-m-d H:i:s');
                $contact->save();
            }
        }
        $number         = convert2english(request('phone'));
        $phone          = phoneValidate($number);
        // if (substr($number, 0, 1) === '0'){
        //     $number = substr($number, 1);
        // }
        // $phone          = $number;        
        if($user = User::where(['phone'=>$phone])->first()){
            if(send_mobile_sms($user->phonekey.$user->phone,$request->sms_message) == true){
                Session::flash('success','تم ارسال الرساله بنجاح.');
                return back();
            }else{
                Session::flash('warning','لم يتم ارسال الرساله ! ... تأكد من بيانات ال SMS');
                return back();
            }            
        }elseif($userMeta = userMeta::where(['phone'=>$phone])->first()){
            $phonekey = ($userMeta->phonekey)??'00966';
            $phonekey = str_replace('+', '00', $phonekey);
            if(send_mobile_sms($phonekey.$userMeta->phone,$request->sms_message) == true){
                Session::flash('success','تم ارسال الرساله بنجاح.');
                return back();
            }else{
                Session::flash('warning','لم يتم ارسال الرساله ! ... تأكد من بيانات ال SMS');
                return back();
            }
        }
        Session::flash('warning','هذا المستخدم غير موجود.');
        return back();
    }

    #send SMS
    public function sendnotification(Request $request){
        $this->validate($request,[
            'phone'       =>'required',
            'notification_message' =>'required|min:5',
            'notification_title'   => 'nullable'
        ]);
        if($request->has('msgid')){
            if( $contact = Contact::find($request->msgid) ){
                $contact->answer .= ' '.$request->notification_message;
                $contact->answer_at = date('Y-m-d H:i:s');
                $contact->save();
            }
        }
        if($user = User::find($request->user_id)){
        /*send notification to provider with admin agree*/
            $devices = userDevices::where(['user_id'=>$user->id])->get();
            $notify_title    = ($request->notification_title)? $request->notification_title : setting('site_title');
            $message_ar   = $request->notification_message;
            $message_en   = $request->notification_message;
            $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'answerContact'];
            sendNotification($devices, $message_ar,$notify_title,$data);
            notify($user->id,'',$notify_title,$message_ar,"user_id:".$user->id,'','answerContact'); 

        /* end of send FCM notification */
            Session::flash('success','تم ارسال الرساله بنجاح.');
            return back();
       
        }
        Session::flash('warning','هذا المستخدم غير موجود.');
        return back();
    }


    #send EMAIL
    public function EMAIL(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'email_message' =>'required|min:1'
        ]);
        if( $contact = Contact::find($request->msgid) ){
            $contact->answer   .= ' '.$request->email_message;
            $contact->answer_at = date('Y-m-d H:i:s');
            $contact->save();
        }
        #check if smtp congiration complete or no
        $checkConfig = SmsEmailNotification::where('type','=','smtp')->first();
        if(
            $checkConfig->username     == "" ||
            $checkConfig->password     == "" ||
            $checkConfig->sender_email == "" ||
            $checkConfig->port         == "" ||
            $checkConfig->host         == ""
        ){
            Session::flash('danger','لم يتم ارسال الرساله ! .. يرجى مراجعة بيانات ال SMTP');
            return back();
        }else{
            Mail::to($request->email)->send(new PublicMessage($request->email_message));
            Session::flash('success','تم ارسال الرساله');
            return back();
        }
    }

    #delete mesage
    public function DeleteMessage(Request $request){
        Contact::findOrFail($request->id)->delete();
        Session::flash('success','تم حذف الرساله');
        return redirect('admin/inbox-page');
    }
}
