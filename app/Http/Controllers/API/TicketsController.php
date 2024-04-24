<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Tickets;
use JWTAuth;
// use DateTime;
use Jenssegers\Date\Date;
use Tymon\JWTAuth\Exceptions\JWTException;
class TicketsController extends Controller{

    public function createTicket(Request $request){
        $validator         = Validator::make($request->all(),[
            'subject'      => 'required',
            'order_id'     => 'required|integer',
            'text'         => 'nullable'
        ]);

        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $ticket = new Tickets();
            $ticket->user_id         = $user->id;
            $ticket->order_id        = $request->order_id;
            $ticket->subject         = $request->subject;
            $ticket->status          = 'open';
            $ticket->month           = date('Y-m');
            $ticket->text            = nl2br( $request->text );
            if($request->hasFile('src')) {
                $src             = $request->file('src');
                $name            = md5($request->file('src')->getClientOriginalName()).time().rand(99999,1000000).'.'.$src->getClientOriginalExtension();
                $destinationPath = public_path('/img/complaint');
                $srcPath         = $destinationPath. "/".  $name;
                $src->move($destinationPath, $name);
                $ticket->src    = $name;
            }            
            $ticket->save();
            $msg = trans('contactus.sent_success');
            return response()->json(successReturnMsg($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function userTickets(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $lang = $request->header('lang');
        $data = [];
        if($tickets = Tickets::where(['user_id'=>$user->id])->orderBy('created_at','DESC')->get()){
            foreach($tickets as $ticket){
                $month = ($ticket->month)?? date('Y-m');
                $data[] = ['id'         => $ticket->id,
                           'order_id '  => $ticket->order_id,
                           'month'      => ($lang == 'en')? date('m',strtotime($month)) : Date::parse($month)->format('m'),
                           'month_name' => ($lang == 'en')? date('F',strtotime($month)) : Date::parse($month)->format('F'),
                           'date'       => date('m-d',strtotime($ticket->created_at)),
                          ];
            }
        }
      return response()->json(successReturn($data));
    }

    public function userTicket(Request $request){
        $validator         = Validator::make($request->all(),[
            'ticket_id'    => 'required',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = $request->header('lang');
            if($ticket = Tickets::where(['id'=>$request->ticket_id,'user_id'=>$user->id])->first()){
                $month = ($ticket->month)?? date('Y-m');
                $data = ['id'         => $ticket->id,
                         'subject'    => $ticket->subject,
                         'user_id'    => $ticket->user_id,
                         'order_id '  => $ticket->order_id,
                         'month'      => ($lang == 'en')? date('m',strtotime($month)) : Date::parse($month)->format('m'),
                         'month_name' => ($lang == 'en')? date('F',strtotime($month)) : Date::parse($month)->format('F'),
                         'text'       => ($ticket->text)??'',
                         'answer'     => ($ticket->answer)??'',
                         'src'        => ($ticket->src)? url('img/complaint/'.$ticket->src): '',
                         'date'       => date('m-d',strtotime($ticket->created_at)),
                      ];
              return response()->json(successReturn($data));
            }
            $msg = trans('contactus.ticketnotfound');
            return response()->json(failReturn($msg));          
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));          
        }      
    }

    public function cancelTicket(Request $request){
        $validator         = Validator::make($request->all(),[
            'ticket_id'    => 'required',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($ticket = Tickets::where(['id'=>$request->ticket_id,'user_id'=>$user->id])->first()){
              $ticket->delete();
              $msg = trans('contactus.cancelTicketsuccess');
              return response()->json(successReturnMsg($msg));
            }
            $msg = trans('contactus.ticketnotfound');
            return response()->json(failReturn($msg));          
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));          
        }    
    }

}
