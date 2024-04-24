<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tickets;
use App\User;
use App\userDevices;
use Session;
use App\Mail\PublicMessage;
use Mail;
use App\SmsEmailNotification;

class TicketsController extends Controller{

    public function tickets(){
        $tickets = Tickets::orderBy('created_at','DESC')->get();
        return view('dashboard.tickets.index',compact('tickets'));
    }

    #show ticket
    public function ticket($id){
        $ticket = Tickets::findOrFail($id);
        $ticket->seen = 'true';
        $ticket->update();        
        return view('dashboard.tickets.ticket',compact('ticket'));
    }

    #send SMS
    public function smsTicket(Request $request){
        $this->validate($request,[
            'phone'       =>'required',
            'sms_message' =>'required|min:5'
        ]);
        if($request->has('msgid')){
            if( $ticket = Tickets::find($request->msgid) ){
                $ticket->answer = $request->sms_message;
                $ticket->answer_at = date('Y-m-d H:i:s');
                $ticket->save();
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
        }
        Session::flash('warning','هذا المستخدم غير موجود.');
        return back();
    }

    public function emailTicket(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'email_message' =>'required|min:1'
        ]);
        if($request->has('msgid')){
            if( $ticket = Tickets::find($request->msgid) ){
                $ticket->answer = $request->email_message;
                $ticket->answer_at = date('Y-m-d H:i:s');
                $ticket->save();
            }
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


    #send notification
    public function notificationTicket(Request $request){
        $this->validate($request,[
            'phone'       =>'required',
            'notification_message' =>'required|min:5',
            'notification_title'   => 'nullable'
        ]);
        if($request->has('msgid')){
            if( $ticket = Tickets::find($request->msgid) ){
                $ticket->answer = $request->notification_message;
                $ticket->answer_at = date('Y-m-d H:i:s');
                $ticket->save();
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
            notify($user->id,'',$message_ar,"user_id:".$user->id,'','answerContact');         
        /* end of send FCM notification */
            Session::flash('success','تم ارسال الرساله بنجاح.');
            return back();
       
        }
        Session::flash('warning','هذا المستخدم غير موجود.');
        return back();
    }

    #delete ticket
    public function deleteTicket(Request $request){
        Tickets::findOrFail($request->id)->delete();
        Session::flash('success','تم حذف الشكوي');
        return redirect('admin/tickets');
    }
}
