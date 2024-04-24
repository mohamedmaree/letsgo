<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
// use Image;
use App\Social;
use App\Ads;
use App\Html;
use App\Setting;
use App\SmsEmailNotification;
use File;
use View;
use Auth;
use App\Country;

class SettingController extends Controller{
    
    public function __construct(){
        $socials     = Social::get();
        $ads         = Ads::orderBy('end_at','DESC')->get();
        $smtp        = SmsEmailNotification::where('type','=','smtp')->first();
        $mobily      = SmsEmailNotification::where('type','=','mobily')->first();
        $yamamah     = SmsEmailNotification::where('type','=','yamamah')->first();
        $oursms      = SmsEmailNotification::where('type','=','oursms')->first();
        $hisms       = SmsEmailNotification::where('type','=','hisms')->first();
        $jawaly      = SmsEmailNotification::where('type','=','4jawaly')->first();
        $unifonic    = SmsEmailNotification::where('type','=','unifonic')->first();
        $gateway     = SmsEmailNotification::where('type','=','gateway')->first();
        $msegat      = SmsEmailNotification::where('type','=','msegat')->first();
        $nexmosms    = SmsEmailNotification::where('type','=','nexmosms')->first();
        $twilio      = SmsEmailNotification::where('type','=','twilio')->first();
        $onesignal   = SmsEmailNotification::where('type','=','onesignal')->first();
        $fcm         = SmsEmailNotification::where('type','=','fcm')->first();
        // $SiteSetting = SiteSetting::first();
        $Html        = Html::first();
        View::share([
            'socials'     => $socials,
            'ads'         => $ads,
            'smtp'        => $smtp,
            'mobily'      => $mobily,
            'yamamah'     => $yamamah,
            'oursms'      => $oursms,
            'hisms'       => $hisms,
            'jawaly'      => $jawaly,
            'unifonic'    => $unifonic,
            'gateway'     => $gateway,
            'msegat'      => $msegat,
            'nexmosms'    => $nexmosms,
            'twilio'      => $twilio,
            'onesignal'   => $onesignal,
            'fcm'         => $fcm,
            // 'SiteSetting' => $SiteSetting,
            'Html'        => $Html
        ]);
    }

    #setting page
    public function Setting(){
        return view('dashboard.setting.setting');
    }

    #add social media
    public function AddSocial(Request $request){
        $this->validate($request,[
            'site_name' =>'required|min:1|max:190',
            'site_link' =>'required|min:5|max:190',
            'add_logo'  =>'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $social = new Social;
        $social->name  = $request->site_name;
        $social->link  = $request->site_link;
        if($request->hasFile('add_logo')) {
            $image           = $request->file('add_logo');
            $name            = md5($request->file('add_logo')->getClientOriginalName()).rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/dashboard/uploads/socialicon/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $social->logo = $name;
        }
            $social->save();
            Session::flash('success','تم الحفظ');
            return back();

    }

    #update social 
    public function UpdateSocial(Request $request)
    {
        $this->validate($request,[
            'edit_site_name' =>'required|min:1|max:190',
            'edit_site_link' =>'required|min:5|max:190',
            'edit_logo' =>'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $social = Social::findOrFail($request->id);
        $social->name  = $request->edit_site_name;
        $social->link  = $request->edit_site_link;

        if($request->hasFile('edit_logo')) {
            $image           = $request->file('edit_logo');
            $name            = md5($request->file('edit_logo')->getClientOriginalName()).rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/dashboard/uploads/socialicon/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $social->logo    = $name;
        }

        $social->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #delete social
    public function DeleteSocial(Request $request){
        $social = Social::findOrFail($request->id);
        File::delete('dashboard/uploads/socialicon/'.$social->logo);
        $social->delete();
        Session::flash('success','تم الحذف بنجاح');
        return back();
    }

    #add Ad
    public function addAd(Request $request){
        $this->validate($request,[
            'ad_end_at' =>'required|date|after:'.date('Y-m-d'),
            'ad_link'   =>'nullable',
            'ad_image'  =>'required',//|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        $ad = new Ads();
        $ad->end_at  = $request->ad_end_at;
        $ad->link    = $request->ad_link;
        $ad->notes   = $request->ad_notes;
        if($request->hasFile('ad_image')) {
            $image           = $request->file('ad_image');
            $name            = md5($request->file('ad_image')->getClientOriginalName()).rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/dashboard/uploads/ads/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $ad->image = $name;
        }
            $ad->save();
            Session::flash('success','تم الحفظ');
            return back();
    }

    #update social 
    public function updateAd(Request $request){
        $this->validate($request,[
            'edit_end_at'    =>'required|date|after:'.date('Y-m-d'),
            'edit_ad_link'   =>'nullable',
            'edit_ad_image'  =>'nullable',//|image|mimes:jpeg,png,jpg,gif,svg'
        ]);        
        $ad = Ads::findOrFail($request->ad_id);
        $ad->end_at  = $request->edit_end_at;
        $ad->link    = $request->edit_ad_link;
        $ad->notes   = $request->edit_notes;
        if($request->hasFile('edit_ad_image')) {
            $image           = $request->file('edit_ad_image');
            $name            = md5($request->file('edit_ad_image')->getClientOriginalName()).rand(99999,1000000).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/dashboard/uploads/ads/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $ad->image = $name;
        }
            $ad->save();
            Session::flash('success','تم الحفظ');
            return back();
    }

    #delete social
    public function deleteAd(Request $request){
        $ad = Ads::findOrFail($request->id);
        File::delete('dashboard/uploads/ads/'.$ad->image);
        $ad->delete();
        Session::flash('success','تم الحذف بنجاح');
        return back();
    }
    #update SMTP
    public function SMTP(Request $request)
    {
        $this->validate($request,[
            'smtp_username'    =>'nullable|min:1|max:190',
            'smtp_sender_email'=>'nullable|min:1|max:190',
            'smtp_sender_name' =>'nullable|min:1|max:190',
            'smtp_password'    =>'nullable|min:1|max:190',
            'smtp_port'        =>'nullable|min:1|max:190',
            'smtp_host'        =>'nullable|min:1|max:190',
            'smtp_encryption'  =>'nullable|min:1|max:190',
        ]);
        if($smtp = SmsEmailNotification::where('type','=','smtp')->first()){
            $smtp->type         = "smtp";
            $smtp->username     = $request->smtp_username;
            $smtp->sender_email = $request->smtp_sender_email;
            $smtp->sender_name  = $request->smtp_sender_name;
            $smtp->password     = $request->smtp_password;
            $smtp->port         = $request->smtp_port;
            $smtp->host         = $request->smtp_host;
            $smtp->encryption   = $request->smtp_encryption;
            $smtp->active       = ($request->smtp_active == 'on')?'true':'false';
            $smtp->save();       
        }else{
            $smtp = new SmsEmailNotification();
            $smtp->type         = "smtp";
            $smtp->username     = $request->smtp_username;
            $smtp->sender_email = $request->smtp_sender_email;
            $smtp->sender_name  = $request->smtp_sender_name;
            $smtp->password     = $request->smtp_password;
            $smtp->port         = $request->smtp_port;
            $smtp->host         = $request->smtp_host;
            $smtp->encryption   = $request->smtp_encryption;
            $smtp->active       = ($request->smtp_active == 'on')?'true':'false';
            $smtp->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update SMS
    // public function SMS(Request $request)
    // {
    //     $this->validate($request,[
    //         'sms_number'      =>'nullable|min:1|max:190',
    //         'sms_password'    =>'nullable|min:1|max:190',
    //         'sms_sender_name' =>'nullable|min:1|max:190'
    //     ]);

    //     $SEN = new SmsEmailNotification();
    //     $SEN->number      = $request->sms_number;
    //     $SEN->password    = $request->sms_password;
    //     $SEN->sender_name = $request->sms_sender_name;
    //     $SEN->save();
    //     Session::flash('success','تم حفظ التعديلات');
    //     return back(); 
    // }

    #update mobily
    public function Mobily(Request $request)
    {
        $this->validate($request,[
            'mobily_number'      =>'nullable|min:1|max:190',
            'mobily_password'    =>'nullable|min:1|max:190',
            'mobily_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($mobily = SmsEmailNotification::where('type','=','mobily')->first()){
            $mobily->type        = "mobily";
            $mobily->number      = $request->mobily_number;
            $mobily->password    = $request->mobily_password;
            $mobily->sender_name = $request->mobily_sender_name;
            $mobily->active       = ($request->mobily_active == 'on')?'true':'false';
            $mobily->save();
        }else{
            $mobily = new SmsEmailNotification();
            $mobily->type        = "mobily";
            $mobily->number      = $request->mobily_number;
            $mobily->password    = $request->mobily_password;
            $mobily->sender_name = $request->mobily_sender_name;
            $mobily->active       = ($request->mobily_active == 'on')?'true':'false';
            $mobily->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update yamamah
    public function yamamah(Request $request)
    {
        $this->validate($request,[
            'yamamah_number'      =>'nullable|min:1|max:190',
            'yamamah_password'    =>'nullable|min:1|max:190',
            'yamamah_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($yamamah = SmsEmailNotification::where('type','=','yamamah')->first()){
            $yamamah->type      = "yamamah";
            $yamamah->number      = $request->yamamah_number;
            $yamamah->password    = $request->yamamah_password;
            $yamamah->sender_name = $request->yamamah_sender_name;
            $yamamah->active      = ($request->yamamah_active == 'on')?'true':'false';
            $yamamah->save();
        }else{
            $yamamah = new SmsEmailNotification();
            $yamamah->type      = "yamamah";
            $yamamah->number      = $request->yamamah_number;
            $yamamah->password    = $request->yamamah_password;
            $yamamah->sender_name = $request->yamamah_sender_name;
            $yamamah->active      = ($request->yamamah_active == 'on')?'true':'false';
            $yamamah->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update Oursms
    public function OurSms(Request $request){
        $this->validate($request,[
            'oursms_number'      =>'nullable|min:1|max:190',
            'oursms_password'    =>'nullable|min:1|max:190',
            'oursms_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($oursms = SmsEmailNotification::where('type','=','oursms')->first()){
            $oursms->type        = "oursms";
            $oursms->number      = $request->oursms_number;
            $oursms->password    = $request->oursms_password;
            $oursms->sender_name = $request->oursms_sender_name;
            $oursms->active      = ($request->oursms_active == 'on')?'true':'false';
            $oursms->save();
        }else{
            $oursms = new SmsEmailNotification();
            $oursms->type        = "oursms";
            $oursms->number      = $request->oursms_number;
            $oursms->password    = $request->oursms_password;
            $oursms->sender_name = $request->oursms_sender_name;
            $oursms->active      = ($request->oursms_active == 'on')?'true':'false';
            $oursms->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update Hisms
    public function HiSms(Request $request)
    {
        $this->validate($request,[
            'hisms_number'      =>'nullable|min:1|max:190',
            'hisms_password'    =>'nullable|min:1|max:190',
            'hisms_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($hisms = SmsEmailNotification::where('type','=','hisms')->first()){
            $hisms->type        = "hisms";
            $hisms->number      = $request->hisms_number;
            $hisms->password    = $request->hisms_password;
            $hisms->sender_name = $request->hisms_sender_name;
            $hisms->active      = ($request->hisms_active == 'on')?'true':'false';
            $hisms->save();
        }else{
            $hisms = new SmsEmailNotification();
            $hisms->type        = "hisms";
            $hisms->number      = $request->hisms_number;
            $hisms->password    = $request->hisms_password;
            $hisms->sender_name = $request->hisms_sender_name;
            $hisms->active      = ($request->hisms_active == 'on')?'true':'false';
            $hisms->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }
    
        #update 4jawaly
    public function jawaly(Request $request)
    {
        $this->validate($request,[
            'jawaly_number'      =>'nullable|min:1|max:190',
            'jawaly_password'    =>'nullable|min:1|max:190',
            'jawaly_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($jawaly = SmsEmailNotification::where('type','=','4jawaly')->first()){
            $jawaly->type        = "4jawaly";
            $jawaly->number      = $request->jawaly_number;
            $jawaly->password    = $request->jawaly_password;
            $jawaly->sender_name = $request->jawaly_sender_name;
            $jawaly->active      = ($request->jawaly_active == 'on')?'true':'false';
            $jawaly->save();
        }else{
            $jawaly = new SmsEmailNotification();
            $jawaly->type        = "4jawaly";
            $jawaly->number      = $request->jawaly_number;
            $jawaly->password    = $request->jawaly_password;
            $jawaly->sender_name = $request->jawaly_sender_name;
            $jawaly->active      = ($request->jawaly_active == 'on')?'true':'false';
            $jawaly->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }
    
    #update unifonic
    public function unifonic(Request $request){
        $this->validate($request,[
            'unifonic_number'      =>'nullable|min:1|max:190',
            'unifonic_password'    =>'nullable|min:1|max:190',
            'unifonic_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($unifonic = SmsEmailNotification::where('type','=','unifonic')->first()){
            $unifonic->type        = "unifonic";
            $unifonic->number      = $request->unifonic_number;
            $unifonic->password    = $request->unifonic_password;
            $unifonic->sender_name = $request->unifonic_sender_name;
            $unifonic->active      = ($request->unifonic_active == 'on')?'true':'false';
            $unifonic->save();
        }else{
            $unifonic = new SmsEmailNotification();
            $unifonic->type        = "unifonic";
            $unifonic->number      = $request->unifonic_number;
            $unifonic->password    = $request->unifonic_password;
            $unifonic->sender_name = $request->unifonic_sender_name;
            $unifonic->active      = ($request->unifonic_active == 'on')?'true':'false';
            $unifonic->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }
    #update hi sms
    public function gateway(Request $request)
    {
        $this->validate($request,[
            'gateway_number'      =>'nullable|min:1|max:190',
            'gateway_password'    =>'nullable|min:1|max:190',
            'gateway_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($gateway = SmsEmailNotification::where('type','=','gateway')->first()){
            $gateway->type        = "gateway";
            $gateway->number      = $request->gateway_number;
            $gateway->password    = $request->gateway_password;
            $gateway->sender_name = $request->gateway_sender_name;
            $gateway->active      = ($request->gateway_active == 'on')?'true':'false';
            $gateway->save();
        }else{
            $gateway = new SmsEmailNotification();
            $gateway->type        = "gateway";
            $gateway->number      = $request->gateway_number;
            $gateway->password    = $request->gateway_password;
            $gateway->sender_name = $request->gateway_sender_name;
            $gateway->active      = ($request->gateway_active == 'on')?'true':'false';
            $gateway->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    public function msegat(Request $request)
    {
        $this->validate($request,[
            'msegat_number'      =>'nullable|min:1|max:190',
            'msegat_password'    =>'nullable|min:1|max:190',
            'msegat_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($msegat = SmsEmailNotification::where('type','=','msegat')->first()){
            $msegat->type        = "msegat";
            $msegat->number      = $request->msegat_number;
            $msegat->password    = $request->msegat_password;
            $msegat->sender_name = $request->msegat_sender_name;
            $msegat->active      = ($request->msegat_active == 'on')?'true':'false';
            $msegat->save();
        }else{
            $msegat = new SmsEmailNotification();
            $msegat->type        = "msegat";
            $msegat->number      = $request->msegat_number;
            $msegat->password    = $request->msegat_password;
            $msegat->sender_name = $request->msegat_sender_name;
            $msegat->active      = ($request->msegat_active == 'on')?'true':'false';
            $msegat->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }
    
    #update Nexmosms
    public function Nexmosms(Request $request)
    {
        $this->validate($request,[
            'nexmosms_number'      =>'nullable|min:1|max:190',
            'nexmosms_password'    =>'nullable|min:1|max:190',
            'nexmosms_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($nexmosms = SmsEmailNotification::where('type','=','nexmosms')->first()){
            $nexmosms->type        = "nexmosms";
            $nexmosms->number      = $request->nexmosms_number;
            $nexmosms->password    = $request->nexmosms_password;
            $nexmosms->sender_name = $request->nexmosms_sender_name;
            $nexmosms->active      = ($request->nexmosms_active == 'on')?'true':'false';
            $nexmosms->save();
        }else{
            $nexmosms = new SmsEmailNotification();
            $nexmosms->type        = "nexmosms";
            $nexmosms->number      = $request->nexmosms_number;
            $nexmosms->password    = $request->nexmosms_password;
            $nexmosms->sender_name = $request->nexmosms_sender_name;
            $nexmosms->active      = ($request->nexmosms_active == 'on')?'true':'false';
            $nexmosms->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update Twilio
    public function Twilio(Request $request)
    {
        $this->validate($request,[
            'twilio_number'      =>'nullable|min:1|max:190',
            'twilio_password'    =>'nullable|min:1|max:190',
            'twilio_sender_name' =>'nullable|min:1|max:190'
        ]);
        if($twilio = SmsEmailNotification::where('type','=','twilio')->first()){
            $twilio->type        = "twilio";
            $twilio->number      = $request->twilio_number;
            $twilio->password    = $request->twilio_password;
            $twilio->sender_name = $request->twilio_sender_name;
            $twilio->active      = ($request->twilio_active == 'on')?'true':'false';
            $twilio->save();
        }else{
            $twilio = new SmsEmailNotification();
            $twilio->type        = "twilio";
            $twilio->number      = $request->twilio_number;
            $twilio->password    = $request->twilio_password;
            $twilio->sender_name = $request->twilio_sender_name;
            $twilio->active      = ($request->twilio_active == 'on')?'true':'false';
            $twilio->save();
        }
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }


    #update onesignal
    public function OneSignal(Request $request)
    {
        $this->validate($request,[
            'oneSignal_application_id' =>'nullable|min:1|max:190',
            'oneSignal_authorization'  =>'nullable|min:1|max:190'
        ]);
        if($onesignal = SmsEmailNotification::where('type','=','onesignal')->first()){
            $onesignal->type           = 'onesignal'; 
            $onesignal->application_id = $request->oneSignal_application_id;
            $onesignal->authorization  = $request->oneSignal_authorization;
            $onesignal->active         = ($request->onesignal_active == 'on')?'true':'false';
            $onesignal->save();
        }else{
            $onesignal = new SmsEmailNotification();
            $onesignal->type           = 'onesignal'; 
            $onesignal->application_id = $request->oneSignal_application_id;
            $onesignal->authorization  = $request->oneSignal_authorization;
            $onesignal->active         = ($request->onesignal_active == 'on')?'true':'false';
            $onesignal->save();            
        }

        Session::flash('success','تم حفظ التعديلات');
        return back(); 
    }

    #update FCM
    public function updateFCM(Request $request)
    {
        $this->validate($request,[
            'fcm_server_key' =>'nullable|min:1|max:190',
            'fcm_sender_id'  =>'nullable|min:1|max:190'
        ]);
        if($fcm = SmsEmailNotification::where('type','=','fcm')->first()){
            $fcm->type       = 'fcm'; 
            $fcm->server_key = $request->fcm_server_key;
            $fcm->sender_id  = $request->fcm_sender_id;
            $fcm->active     = ($request->fcm_active == 'on')?'true':'false';
            $fcm->save();
        }else{
            $fcm = new SmsEmailNotification();
            $fcm->type       = 'fcm'; 
            $fcm->server_key = $request->fcm_server_key;
            $fcm->sender_id  = $request->fcm_sender_id;
            $fcm->active     = ($request->fcm_active == 'on')?'true':'false';
            $fcm->save();            
        }

        Session::flash('success','تم حفظ التعديلات');
        return back(); 
    }

    #update SiteSetting
    public function SiteSetting(Request $request){
        $this->validate($request,[
            'site_name'        =>'nullable|min:1|max:190',
            'distance'         =>'nullable',
            'site_currency_ar' =>'nullable|min:2|max:190',
            'site_currency_en' =>'nullable|min:2|max:190',
            'logo'             =>'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $SiteSetting = Setting::where('set_key','=','site_title')->first();
        $SiteSetting->set_value = ($request->site_name)??'';
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','site_email')->first();
        $SiteSetting->set_value = ($request->site_email)??'';
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','site_phone')->first();
        $SiteSetting->set_value = ($request->site_phone)??'';
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','captains_support_phone')->first();
        $SiteSetting->set_value = ($request->captains_support_phone)??'';
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','clients_support_phone')->first();
        $SiteSetting->set_value = ($request->clients_support_phone)??'';
        $SiteSetting->save();                
        $SiteSetting = Setting::where('set_key','=','site_currency_ar')->first();
        $SiteSetting->set_value = ($request->site_currency_ar)??'';
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','site_currency_en')->first();
        $SiteSetting->set_value = ($request->site_currency_en)??'';
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','free_balance')->first();
        $SiteSetting->set_value = ($request->free_balance)??0;
        $SiteSetting->save(); 
        // $SiteSetting = Setting::where('set_key','=','order_close_time')->first();
        // $SiteSetting->set_value = ($request->order_close_time)??'';
        // $SiteSetting->save();                                         
        $SiteSetting = Setting::where('set_key','=','distance')->first();
        $SiteSetting->set_value = ($request->distance)??0;
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','min_distance')->first();
        $SiteSetting->set_value = ($request->min_distance)??0;
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','max_distance')->first();
        $SiteSetting->set_value = ($request->max_distance)??0;
        $SiteSetting->save(); 
        // $SiteSetting = Setting::where('set_key','=','km_price')->first();
        // $SiteSetting->set_value = ($request->km_price)??0;
        // $SiteSetting->save(); 
        // $SiteSetting = Setting::where('set_key','=','min_order_price')->first();
        // $SiteSetting->set_value = ($request->min_order_price)??0;
        // $SiteSetting->save(); 
        // $SiteSetting = Setting::where('set_key','=','client_cancel')->first();
        // $SiteSetting->set_value = ($request->client_cancel)??0;
        // $SiteSetting->save(); 
        // $SiteSetting = Setting::where('set_key','=','captain_cancel')->first();
        // $SiteSetting->set_value = ($request->captain_cancel)??0;
        // $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','start_day')->first();
        $SiteSetting->set_value = ($request->start_day)??'';
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','first_rush_hour')->first();
        $SiteSetting->set_value = ($request->first_rush_hour)??'';
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','first_rush_hour_percentage')->first();
        $SiteSetting->set_value = ($request->first_rush_hour_percentage)??'';
        $SiteSetting->save();         
        $SiteSetting = Setting::where('set_key','=','second_rush_hour')->first();
        $SiteSetting->set_value = ($request->second_rush_hour)??'';
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','second_rush_hour_percentage')->first();
        $SiteSetting->set_value = ($request->second_rush_hour_percentage)??'';
        $SiteSetting->save();    
        $SiteSetting = Setting::where('set_key','=','third_rush_hour')->first();
        $SiteSetting->set_value = ($request->third_rush_hour)??'';
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','third_rush_hour_percentage')->first();
        $SiteSetting->set_value = ($request->third_rush_hour_percentage)??'';
        $SiteSetting->save();                                           
        $SiteSetting = Setting::where('set_key','=','site_percentage')->first();
        $SiteSetting->set_value = ($request->site_percentage)??'';
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','added_value')->first();
        $SiteSetting->set_value = ($request->added_value)??0;
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','wasl_value')->first();
        $SiteSetting->set_value = ($request->wasl_value)??0;
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','stc_percentage')->first();
        $SiteSetting->set_value = ($request->stc_percentage)??0;
        $SiteSetting->save(); 
        
        // $SiteSetting = Setting::where('set_key','=','max_withdraw_day')->first();
        // $SiteSetting->set_value = ($request->max_withdraw_day)??'';
        // $SiteSetting->save(); 
        // $SiteSetting = Setting::where('set_key','=','withdraw_block_hours')->first();
        // $SiteSetting->set_value = ($request->withdraw_block_hours)??'';
        // $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','allow_debt_captain')->first();
        $SiteSetting->set_value = ($request->allow_debt_captain =='on')?'true':'false';
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','max_debt_captain')->first();
        $SiteSetting->set_value = ($request->max_debt_captain)??0;
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','allow_debt_client')->first();
        $SiteSetting->set_value = ($request->allow_debt_client =='on')?'true':'false';
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','max_debt_client')->first();
        $SiteSetting->set_value = ($request->max_debt_client)??0;
        $SiteSetting->save();          
        $SiteSetting = Setting::where('set_key','=','max_tips')->first();
        $SiteSetting->set_value = ($request->max_tips)??0;
        $SiteSetting->save();                                         
        $SiteSetting = Setting::where('set_key','=','invite_client_balance')->first();
        $SiteSetting->set_value = convert2english( ($request->invite_client_balance)??0 );        
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','invite_captain_balance')->first();
        $SiteSetting->set_value = convert2english( ($request->invite_captain_balance)??0 );        
        $SiteSetting->save();   
        $SiteSetting = Setting::where('set_key','=','agree_message')->first();
        $SiteSetting->set_value = $request->agree_message;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','refuse_message')->first();
        $SiteSetting->set_value = $request->refuse_message;        
        $SiteSetting->save();    
        
        $SiteSetting = Setting::where('set_key','=','ambassador_num_orders')->first();
        $SiteSetting->set_value = $request->ambassador_num_orders;        
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','ambassador_num_days')->first();
        $SiteSetting->set_value = $request->ambassador_num_days;        
        $SiteSetting->save();  
        $SiteSetting = Setting::where('set_key','=','ambassador_balance')->first();
        $SiteSetting->set_value = $request->ambassador_balance;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','newOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->newOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','newOrder_msg_en')->first();
        $SiteSetting->set_value = $request->newOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','attachOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->attachOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','attachOrder_msg_en')->first();
        $SiteSetting->set_value = $request->attachOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','AcceptOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->AcceptOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','AcceptOrder_msg_en')->first();
        $SiteSetting->set_value = $request->AcceptOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','activeCaptain_msg_ar')->first();
        $SiteSetting->set_value = $request->activeCaptain_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','activeCaptain_msg_en')->first();
        $SiteSetting->set_value = $request->activeCaptain_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','block_user_msg_ar')->first();
        $SiteSetting->set_value = $request->block_user_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','block_user_msg_en')->first();
        $SiteSetting->set_value = $request->block_user_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','delete_user_msg_ar')->first();
        $SiteSetting->set_value = $request->delete_user_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','delete_user_msg_en')->first();
        $SiteSetting->set_value = $request->delete_user_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','inWayToOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->inWayToOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','inWayToOrder_msg_en')->first();
        $SiteSetting->set_value = $request->inWayToOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','arrivedToOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->arrivedToOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','arrivedToOrder_msg_en')->first();
        $SiteSetting->set_value = $request->arrivedToOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','startJourney_msg_ar')->first();
        $SiteSetting->set_value = $request->startJourney_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','startJourney_msg_en')->first();
        $SiteSetting->set_value = $request->startJourney_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','withdrawOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->withdrawOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','withdrawOrder_msg_en')->first();
        $SiteSetting->set_value = $request->withdrawOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','finishSimpleOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->finishSimpleOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','finishSimpleOrder_msg_en')->first();
        $SiteSetting->set_value = $request->finishSimpleOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','addedBalance_msg_ar')->first();
        $SiteSetting->set_value = $request->addedBalance_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','addedBalance_msg_en')->first();
        $SiteSetting->set_value = $request->addedBalance_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','ConfirmfinishSimpleOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->ConfirmfinishSimpleOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','ConfirmfinishSimpleOrder_msg_en')->first();
        $SiteSetting->set_value = $request->ConfirmfinishSimpleOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','cancelOrder_msg_ar')->first();
        $SiteSetting->set_value = $request->cancelOrder_msg_ar;        
        $SiteSetting->save(); 
        $SiteSetting = Setting::where('set_key','=','cancelOrder_msg_en')->first();
        $SiteSetting->set_value = $request->cancelOrder_msg_en;        
        $SiteSetting->save(); 

        $SiteSetting = Setting::where('set_key','=','site_logo')->first();
        if($request->hasFile('logo')) {
            File::delete('/dashboard/uploads/setting/site_logo/logo.png');
            $image           = $request->file('logo');
            $name            = 'logo.png';
            $destinationPath = public_path('/dashboard/uploads/setting/site_logo/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $SiteSetting->set_value = $name;
        }
        $SiteSetting = Setting::where('set_key','=','payment_page_background')->first();
        if($request->hasFile('payment_page_background')) {
            $image           = $request->file('payment_page_background');
            $name            = 'payment_page_background.png';
            $destinationPath = public_path('/dashboard/uploads/setting/site_logo/');
            $imagePath       = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $SiteSetting->set_value = $name;
        }
        
        $SiteSetting->save();
        // $SiteSetting = Setting::where('set_key','=','guide_video')->first();
        // if($request->hasFile('guide_video')) {
        //     File::delete('/dashboard/uploads/setting/guide_video/'.$SiteSetting->guide_video);
        //     $video           = $request->file('guide_video');
        //     $name            = 'guide_video.'.$video->getClientOriginalExtension();
        //     $destinationPath = public_path('/dashboard/uploads/setting/guide_video/');
        //     $videoPath       = $destinationPath. "/".  $name;
        //     $video->move($destinationPath,$name);
        //     $SiteSetting->set_value = $name;
        // }
        // $SiteSetting->save();        
        Session::flash('success','تم حفظ التعديلات');
        return redirect('admin/setting'); 
    }

    public function updatePlaceTypes(Request $request){
        $placetypes = '';
        if($request->has('placetypes')){
            $placetypes = implode("|", $request->placetypes);
        }
        $PlaceTypes = Setting::where('set_key','=','place_types')->first();
        $PlaceTypes->set_value = $placetypes;
        $PlaceTypes->save();
        Session::flash('success','تم حفظ التعديلات');
        return back(); 
    }

    #update SEO
    public function SEO(Request $request){
        $SiteSetting = Setting::where('set_key','=','site_description')->first();
        $SiteSetting->set_value = $request->site_description;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','site_tagged')->first();
        $SiteSetting->set_value = $request->site_tagged;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','site_copyrigth')->first();
        $SiteSetting->set_value = $request->site_copyrigth;
        $SiteSetting->save();              
        Session::flash('success','تم حفظ التعديلات');
        return back(); 
    }

    #update siteCopyRight
    public function updatesiteTermsAndPrivacy(Request $request){
        $SiteSetting = Setting::where('set_key','=','terms_ar')->first();
        $SiteSetting->set_value = $request->terms_ar;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','terms_en')->first();
        $SiteSetting->set_value = $request->terms_en;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','privacy_ar')->first();
        $SiteSetting->set_value = $request->privacy_ar;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','privacy_en')->first();
        $SiteSetting->set_value = $request->privacy_en;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','about_app_ar')->first();
        $SiteSetting->set_value = $request->about_app_ar;
        $SiteSetting->save();
        $SiteSetting = Setting::where('set_key','=','about_app_en')->first();
        $SiteSetting->set_value = $request->about_app_en;
        $SiteSetting->save();        
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update email template
    public function EmailTemplate(Request $request)
    {
        $html = Html::first();
        $html->email_font_color   = $request->email_font_color;
        $html->email_header_color = $request->email_header_color;
        $html->email_footer_color = $request->email_footer_color;
        $html->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }
    
    #update api google places key
    public function updategooglePlacesKey(Request $request){
        $SiteSetting = Setting::where('set_key','=','google_places_key')->first();
        $SiteSetting->set_value = $request->google_places_key;
        $SiteSetting->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }
    
    public function updatewaslApiKey(Request $request){
        $SiteSetting = Setting::where('set_key','=','wasl_api_key')->first();
        $SiteSetting->set_value = $request->wasl_api_key;
        $SiteSetting->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update api currency converter key
    public function updateCurrencyConverter(Request $request){
        $SiteSetting = Setting::where('set_key','=','currencyconverterapi')->first();
        $SiteSetting->set_value = $request->currencyconverterapi;
        $SiteSetting->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update api google analytics
    public function GoogleAnalytics(Request $request)
    {
        $html = Html::first();
        $html->google_analytics   = $request->google_analytics;
        $html->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    #update api google live chat
    public function LiveChat(Request $request)
    {
        $html = Html::first();
        $html->live_chat   = $request->live_chat;
        $html->save();
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }


}
