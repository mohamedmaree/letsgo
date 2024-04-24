<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use File;
use Auth;
use App\Conversation;
use App\orderImages;
use App\cancelReasons;
use App\orderWithdrawReasons;
use Illuminate\Support\Facades\DB; 
use App\Exports\ordersExport;
use App\Exports\foodOrdersExport;
use App\Exports\externalOrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use App\userDevices;

class OrderController extends Controller{

    public function captainShowOrder($id = '' ,$lang = 'ar'){
        $lang  = ($lang)??'ar';
        app()->setLocale($lang);
        \Carbon\Carbon::setLocale($lang);
        $order = Order::with('user','captain')->findOrFail($id);
        $user  = $order->user; 
        return view('orders.captainShowOrder',compact('order','user','lang'));
    }  

  // ***************** Admin Part **************//
    #orders page
    public function Adminorders(){
      $orders = Order::with('user','captain')->where('order_type','=','trip')->latest()->get();
      return view('dashboard.orders.index',compact('orders',$orders));
    }
    public function downloadAllOrders(){
        return Excel::download( new ordersExport('all'), 'AllOrders.xlsx');        
    }

    public function openOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','open')->orderBy('created_at','desc')->get();
      return view('dashboard.orders.open',compact('orders',$orders));
    }
    public function downloadOpenOrders(){
        return Excel::download( new ordersExport('open'), 'OpenOrders.xlsx');        
    }

    public function inprogressOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','inprogress')->orderBy('created_at','desc')->get();
      return view('dashboard.orders.inprogress',compact('orders',$orders));
    }
    public function downloadInprogressOrders(){
        return Excel::download( new ordersExport('inprogress'), 'InprogressOrders.xlsx');        
    }

    public function finishedOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','finished')->orderBy('created_at','desc')->get();
      return view('dashboard.orders.finished',compact('orders',$orders));
    }
    public function downloadFinishedOrders(){
        return Excel::download( new ordersExport('finished'), 'FinishedOrders.xlsx');        
    }
    
    public function closedOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','closed')->orderBy('created_at','desc')->get();
      return view('dashboard.orders.closed',compact('orders',$orders));
    }
    public function downloadClosedOrders(){
        return Excel::download( new ordersExport('closed'), 'ClosedOrders.xlsx');        
    }

    public function showOrder($id = ''){
        if ($order = Order::with('user', 'captain')->find($id)) {

            $captain = $order->captain;

            $start_lat  = doubleval($order->start_lat);
            $start_long = doubleval($order->start_long);
            $distance   = floatval((setting('distance') * 0.1 ) / 15 );
            $min_lat    = $start_lat  - $distance;
            $min_long   = $start_long - $distance;
            $max_lat    = $start_lat  + $distance;
            $max_long   = $start_long + $distance;             
              $allCaptains   = User::/*ActiveFromOneDay()->*/where('users.captain','=','true')
                                                                ->where('role','=','0')
                                                                 ->where('users.have_order','=','false')
                                                                 ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                 // ->where('users.available','=','true')
                                                                 ->get();

            return view('dashboard.orders.showOrder', compact('order'), get_defined_vars());
        } else {
            return redirect('admin/Adminorders');
        }
    }

    public function getNearformOrderAvailableCaptainsLocation($id = '',$types = 'all'){
      $types = explode(',', $types);
      $data['captains'] = [];
      if ($order = Order::with('user', 'captain')->find($id)) {
          $start_lat  = doubleval($order->start_lat);
          $start_long = doubleval($order->start_long);
          $distance   = floatval((setting('distance')* 0.1 ) / 15 );
          $min_lat    = $start_lat  - $distance;
          $min_long   = $start_long - $distance;
          $max_lat    = $start_lat  + $distance;
          $max_long   = $start_long + $distance;  
          if(in_array('all', $types) || (in_array('available', $types) && in_array('offline', $types) )){
              $data['captains']   = User::/*ActiveFromOneDay()->*/where('users.captain','=','true')
                                                                  ->where('role','=','0')
                                                                  ->where('users.have_order','=','false')
                                                                  ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                  ->when($order->captain_id, function ($query)use($order) {
                                                                      return $query->orwhere('users.id', $order->captain_id);
                                                                  })
                                                                   // ->where('users.available','=','true')
                                                                  ->get();
          }elseif ((!in_array('all', $types)) &&  (in_array('available', $types))) {
              $data['captains']   = User::/*ActiveFromOneDay()->*/where('users.captain','=','true')
                                                                  ->where('role','=','0')
                                                                  ->where('users.have_order','=','false')
                                                                  ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                  ->where('users.available','=','true')
                                                                  ->when($order->captain_id, function ($query)use($order) {
                                                                      return $query->orwhere('users.id', $order->captain_id);
                                                                  })
                                                                   ->get();
          }elseif ((!in_array('all', $types)) &&  (in_array('offline', $types))) {
              $data['captains']   = User::/*ActiveFromOneDay()->*/where('users.captain','=','true')
                                                                  ->where('role','=','0')
                                                                  ->where('users.have_order','=','false')
                                                                  ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                  ->where('users.available','=','false')
                                                                  ->when($order->captain_id, function ($query)use($order) {
                                                                      return $query->orwhere('users.id', $order->captain_id);
                                                                  })
                                                                   ->get();
          }           
      }      

      return response()->json(successReturn($data));
  }

  public function attachOrderToCaptain(Request $request)
  {
      $order_id = $request->order_id;
      $captain_id = $request->captain_id;

      if ($order = Order::find($order_id)) {
          if($oldcaptain = $order->captain){
              $oldcaptain->have_order = 'false';
              $oldcaptain->save();
          }
          if ($captain = User::find($captain_id)) {
              
              $order->captain_id  = $captain->id;
              $order->status      = 'inprogress';
              $order->reception_time = date('Y-m-d H:i:s');
              $order->car_id         = $captain->currentCar ? $captain->currentCar->id : null;
              $order->save();
              $captain->have_order = 'true';
              $captain->save();
              
              $devices = userDevices::where(['user_id' => $captain->id])->get();
          }
          
          //send notification for captain
          $notify_title = setting('site_title');
        //   $message_ar = 'هناك رحلة مرفقة إليك';
        //   $message_en = 'There is a trip attached to you';
          $message_ar = setting('attachOrder_msg_ar');
          $message_en = setting('attachOrder_msg_en');
          $data = ['title' => $notify_title, 'message_en' => $message_en, 'message_ar' => $message_ar, 'key' => 'newOrder','order_id'=>$order->id,'order_status'=>'open','type' => $order->type];
          sendNotification($devices, $message_ar, $notify_title, $data);
          notify($captain->id, '', $notify_title, 'order.attachOrder', "order_id:" . $order->id, '', 'newOrder');
           

            /********start create conversation between users **/
            $conversation_id = 0;
            if($order->user_id){
              if($conv = Conversation::where(['user1' => $order->user_id,'user2' => $captain->id,'order_id'=> $order->id])->first()){
                  $conversation_id = $conv->id;
              }else{
                  $conv = new Conversation();
                  $conv->user1    = $order->user_id;
                  $conv->user2    = $captain->id;
                  $conv->order_id = $order->id;
                  $conv->save();
                  $conversation_id = $conv->id;
              } 
            } 
            /********end create conversation between users **/

          //** send notification to client with accept order **//
              $devices         = userDevices::where(['user_id'=>$order->user_id])->get();
              $notify_title_ar = 'الموافقة علي الرحلة';
              $notify_title_en = 'Accept The Trip';
            //   $message_ar      = 'قام '.$captain->name.' بالموافقة علي الرحلة .';
            //   $message_en      = $captain->name.' accept the trip.';
              $message_ar = replacePlaceholders(setting('AcceptOrder_msg_ar'),['name' => $captain->name]);
              $message_en = replacePlaceholders(setting('AcceptOrder_msg_en'),['name' => $captain->name]);
              $notifyData      = ['title_ar' => $notify_title_ar,'title_en' =>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'AcceptOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
              sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
              notify($order->user_id,$order->captain_id,'order.AcceptTripTitle','order.AcceptTrip',"order_id:".$order->id.':conversation_id:'.$conversation_id,$order->status,'AcceptOrder');                  
            //** end send notification to client with accept order **//


          History(Auth::user()->id, 'بإرفاق رحله جديده رقم"' . $order->id . ' الي ' . $captain->name);
      }
      $msg = 'order.attachSuccessfully';
      return response()->json(successReturnMsg($msg));
  } 

    #update order
    public function AdminupdateOrder(Request $request){
        $this->validate($request,[
            'id'     =>'required|integer',
            'edit_start_address' =>'required|min:2|max:190',
            'edit_end_address'   =>'required|min:2|max:190',
        ]);

        $order = Order::findOrFail($request->id);
        $firstmsg = 'بتحديث الرحلة رقم "#'.$order->id.'"<br/>';
        $msg = '';
        if( ($request->has('edit_start_address')) && ($request->edit_start_address != $order->start_address) ) {
            $msg .= 'مكان الانطلاق من '.$order->start_address .' الي '.$request->edit_start_address.'<br/>';
            $order->start_address     = $request->edit_start_address;
        }
        if( ($request->has('edit_end_address')) && ($request->edit_end_address != $order->end_address) ) {
            $msg .= 'مكان الوصول من '.$order->end_address .' الي '.$request->edit_end_address.'<br/>';
            $order->end_address     = $request->edit_end_address;
        }  
        if( ($request->has('edit_price')) && ($request->edit_price != $order->price) ) {
            $msg .= 'السعر من '.$order->price .' الي '.$request->edit_price.' '.$order->currency_ar.'<br/>';
            $order->price               = $request->edit_price;
        } 
        if( ($request->has('edit_status')) && ($request->edit_status != $order->status) ) {
            $msg .= 'الحالة من '.$order->status .' الي '.$request->edit_status.'<br/>';
            $order->status               = $request->edit_status;
        }  
        if( ($request->has('edit_notes')) && ($request->edit_notes != $order->notes) ) {
            $msg .= 'تفاصيل الرحلة من '.$order->notes .' الي '.$request->edit_notes.'<br/>';
            $order->notes               = $request->edit_notes;
        }                 
        $order->save();
        if( $order->status != 'inprogress'){
            if($captain = $order->captain){
                $captain->have_order = 'false';
                $captain->save();
            }
        }
        if($msg){
           History(Auth::user()->id,$firstmsg.$msg);
        }
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete order
    public function AdmindeleteOrder(Request $request){
            $order = Order::findOrFail($request->id);
            if($captain = $order->captain){
                $captain->have_order = 'false';
                $captain->save();
            }
            // File::delete('img/order/'.$order->image);
            // DB::table('order_users')->where(['order_id'=>$order->id])->delete();            
            // DB::table('order_bids')->where(['order_id'=>$order->id])->delete();            
            DB::table('order_paths')->where(['order_id'=>$order->id])->delete();            
            History(Auth::user()->id,'بحذف الرحلة رقم #'.$order->id);
            $order->delete();
            return back()->with('success','تم الحذف');
    }

    public function AdmincloseOrder(Request $request){
            $order = Order::findOrFail($request->id);
            $order->status = 'closed';
            $order->save();
            if($captain = $order->captain){
               $captain->have_order = 'false';
               $captain->save();
            }
            History(Auth::user()->id,'إغلاق الرحلة رقم #'.$order->id);
            return back()->with('success','تم الإغلاق.');
    }

    public function AdminfinishOrder(Request $request){
            $order = Order::findOrFail($request->id);
            $order->status = 'finished';
            $order->end_journey_time = date('Y-m-d H:i:s');
            $order->save();
            if($captain = $order->captain){
                $captain->have_order = 'false';
                $captain->save();
            }
            History(Auth::user()->id,'إنهاء الرحلة ');
            return back()->with('success','تم إنهاء الرحلة رقم #'.$order->id);
    }

    public function orderWithdrawReasons(){
      $reasons = orderWithdrawReasons::with('user','order')->where('type','=','trip')->orderBy('order_id','DESC')->orderBy('created_at','DESC')->get();
      return view('dashboard.orders.orderWithdrawReasons',compact('reasons',$reasons));
    }

    #delete order
    public function deleteOrderWithdrawReason(Request $request){
            $orderWithdrawReason = orderWithdrawReasons::findOrFail($request->id);
            $orderWithdrawReason->delete();
            History(Auth::user()->id,'بحذف سبب انسحاب العضو من رحلة ');
            return back()->with('success','تم الحذف');
    }   

    public function cancelWithdrawReasons(){
        $reasons = cancelReasons::orderBy('id','ASC')->get();
        return view('dashboard.orders.reasons',compact('reasons',$reasons));
    }

    #add reason
    public function createReason(Request $request){
        $this->validate($request,[
            'type'      => 'required',
            'reason_ar' => 'required',
            'reason_en' => 'required'
        ]);

        $reason = new cancelReasons();
        $reason->type      = $request->type;
        $reason->reason_ar = $request->reason_ar;
        $reason->reason_en = $request->reason_en;
        $reason->save();
        History(Auth::user()->id,'بأضافة سبب جديد');
        return back()->with('success','تم اضافة السبب بنجاح');
    }

    #update reason
    public function updateReason(Request $request){
        $this->validate($request,[
            'id'       => 'required',
            'edit_reason_ar'       => 'required',
            'edit_reason_en'       => 'required',
            'edit_type'      => 'required'
        ]);

        if($reason = cancelReasons::find($request->id)){       
          $reason->type       = $request->edit_type;
          $reason->reason_ar  = $request->edit_reason_ar;
          $reason->reason_en  = $request->edit_reason_en;
          $reason->save();
          History(Auth::user()->id,'تعديل أسباب الإغلاق والنسحاب.');
          return back()->with('success','تم التعديل بنجاح');
        }
    }

    #delete user
    public function DeleteReason(Request $request){
            $reason = cancelReasons::findOrFail($request->id);
            $reason->delete();
            History(Auth::user()->id,'بحذف سبب الإغلاق والانسحاب.');
            return back()->with('success','تم الحذف');
    }
//***************** food orders from inside app*******************//
        #orders page
    public function AdminFoodOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','food')->where('order_src','=','internal')->latest()->get();
      return view('dashboard.foodorders.index',compact('orders',$orders));
    }
    public function downloadAllFoodOrders(){
        return Excel::download( new foodOrdersExport('all'), 'AllOrders.xlsx');        
    }

    public function FoodOpenOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','food')->where('order_src','=','internal')->where('status','=','open')->orderBy('created_at','desc')->get();
      return view('dashboard.foodorders.open',compact('orders',$orders));
    }
    public function downloadFoodOpenOrders(){
        return Excel::download( new foodOrdersExport('open'), 'OpenOrders.xlsx');        
    }

    public function FoodInprogressOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','food')->where('order_src','=','internal')->where('status','=','inprogress')->orderBy('created_at','desc')->get();
      return view('dashboard.foodorders.inprogress',compact('orders',$orders));
    }
    public function downloadFoodInprogressOrders(){
        return Excel::download( new foodOrdersExport('inprogress'), 'InprogressOrders.xlsx');        
    }

    public function FoodFinishedOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','food')->where('order_src','=','internal')->where('status','=','finished')->orderBy('created_at','desc')->get();
      return view('dashboard.foodorders.finished',compact('orders',$orders));
    }
    public function downloadFoodFinishedOrders(){
        return Excel::download( new foodOrdersExport('finished'), 'FinishedOrders.xlsx');        
    }
    
    public function FoodClosedOrders(){
      $orders = Order::with('user','captain')->where('order_type','=','food')->where('order_src','=','internal')->where('status','=','closed')->orderBy('created_at','desc')->get();
      return view('dashboard.foodorders.closed',compact('orders',$orders));
    }
    public function downloadFoodClosedOrders(){
        return Excel::download( new foodOrdersExport('closed'), 'ClosedOrders.xlsx');        
    }

    public function FoodOrderWithdrawReasons(){
      $reasons = orderWithdrawReasons::with('user','order')->where('type','=','food')->orderBy('order_id','DESC')->orderBy('created_at','DESC')->get();
      return view('dashboard.foodorders.orderWithdrawReasons',compact('reasons',$reasons));
    }


}
