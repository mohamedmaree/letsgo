<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\AdminConversation;
use App\AdminMessage;
use App\User;
// use LRedis;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;
class ConversationController extends Controller{
    // public function store(Request $request){
    //     $c1 = Conversation::where('user1',Auth::user()->id)->where('user2',$request->user_id)->count();
    //     $c2 = Conversation::where('user2',Auth::user()->id)->where('user1',$request->user_id)->count();
    //     if($c1 != 0 || $c2 != 0)
    //         return redirect()->back();

    //     $c = new Conversation();
    //     $c->user1 = Auth::user()->id;
    //     $c->user2 = $request->user_id;
    //     $c->save();

    //     return redirect()->back();
    // }
    // public function show($id){
    //     $conversation = Conversation::findOrFail($id);
    //     if($conversation->user1 == Auth::user()->id || $conversation->user2 == Auth::user()->id)
    //         return view('conversation.show',compact('conversation'));

    //     return redirect()->back();
    // }

  // ***************** Admin Part **************//
    #conversations page
    public function conversations(){
      $conversations = Conversation::with('firstuser','seconduser')->latest()->get();
      return view('dashboard.conversations.index',compact('conversations',$conversations));
    }

    public function chat($id=''){
      $conversation = Conversation::with('firstuser','seconduser')->findOrFail($id);
      $messages     = Message::with('user')->where('conversation_id','=',$id)->orderBy('created_at','ASC')->get();
      return view('dashboard.conversations.chat',compact('messages','conversation'));
    }

    #delete order
    public function deleteConversation(Request $request){
            $conversation = Conversation::findOrFail($request->id);
            // File::delete('img/order/'.$order->image);
            DB::table('messages')->where(['conversation_id'=>$conversation->id])->delete();
            $conversation->delete();
            
            History(Auth::user()->id,'بحذف المحادثة ');
            return redirect('admin/conversations')->with('success','تم الحذف');
    }    

    public function adminConversations(Request $request, $user_id = null)
    {

        if(!$user_id) {
            $avatar = '';
            $conversation = 0;
            $messages = [];
            $receiver_id = 0;

            return view('dashboard.admin_conversations.index', compact('messages', 'conversation', 'receiver_id', 'avatar'));
        }

        $user = User::findOrFail($user_id);

        if (!($conversation = AdminConversation::where(['user1' => $user->id])->first())) {
            $conversation = new AdminConversation();
            $conversation->user1 = $user->id;
            $conversation->user2 = 0;
            $conversation->save();
        }
        $conversation = $conversation->id;
        $messages = AdminMessage::with('user')->where(['admin_conversation_id' => $conversation])->orderBy('created_at', 'ASC')->get();
        $reader_id = $user->id;
        $avatar = url('img/user/'.$user->avatar);

        $receiver_id = $user->id;
        $data['conversation'] = $conversation;
        $data['receiver_id'] = $receiver_id;
        $data['avatar'] = url('img/user/'.$user->avatar);
        $data['messages'] = $messages;
        $reader_id = $user->id;
        AdminMessage::makeMessagesAsRead($messages, $reader_id);

        if (\request()->ajax()) {
            return successReturn($data);
        }
        return view('dashboard.admin_conversations.index', compact('messages', 'conversation', 'receiver_id', 'avatar'));
    }

    public function getAllUsers(Request $request)
    {
        $users = new User;
        $users = $users->join('admin_conversations', 'admin_conversations.user1', '=', 'users.id');
        $users = $users->select('users.*');
        $users = $users->orderBy('admin_conversations.last_message_at', 'DESC');
        $users = $users->distinct();
        if($request->has('q')) {
            $users = $users->where('users.name', 'like', '%' . $request->q . '%');
        }
        $users->whereHas('conversation');
        $users->with('conversation');
        $users = $users->paginate(25);

        $list = '';

        if ($request->ajax()) {
            foreach ($users as $user) {

                $avatar = ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png');
                $list .= '
                <a id="user-section-'.$user->id.'" class="media border-0 bg-blue-grey bg-lighten-5 user-section"
                   data-user-id="'. $user->id .'">
                   <div class="roomChat '. ($user->unreadConversationMessages < 1  ? "" : "unread-message" ) .' ">
                    <div class="media-left pr-1">
                    <span class="avatar avatar-md avatar-online">
                        <img class="media-object rounded-circle"
                        src="'. $avatar .'" alt="">
                    </span>
                    </div>
                    <div id="media-body-'.$user->id.'" class="media-body" title="'. $user->lastMessage .'">
                        <h6 class="list-group-item-heading">'. $user->name .' (0'.$user->phone.')'.'</h6>
                        <p class="list-group-item-text '. ($user->unreadConversationMessages < 1  ? "text-muted" : "text-bold" ) .'  mb-0">
                            '. Str::limit($user->lastMessage, 25) .'
                        <span class="float-right primary">
                            <span id="message-count-'. $user->id .'" class="badge badge-pill badge-primary
                            message-count '. ($user->unreadConversationMessages < 1  ? "hidden" : "" ) .' ">'. $user->unreadConversationMessages .'</span>
                        </span>
                        <span class="float-right primary">

                        </span>
                        </p>
                    </div>
                                    </div>
                </a>
';
                        // <form action="'. route('deleteAdminConversation', $user->conversation) .'" method="POST" >
                        //     '. csrf_field() .'
                        //     <button type="submit" id="delete" class="conversation-delete reset" title="حذف المحادثة" >
                        //     <i class="icon-trash"></i></button>
                        // </form>
            }
            return  $list;
        }
    }

    public function uploadFile(Request $request){
        $validator    = Validator::make($request->all(),[
            // 'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'image'    => 'required',
        ]);
        if($validator->passes()){
            if($request->hasFile('image')) {
                $image            = $request->file('image');
                $name            = md5($request->file('image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/chatuploads');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $data = ['name' => $name,'url'=> url('chatuploads/'.$name) ];
                return response()->json(successReturn($data));
            }
            $msg = "image required";
            return failReturn($msg);
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return failReturn($msg);
        }
    }

    public function deleteAdminConversation(AdminConversation $adminConversation)
    {
        $adminConversation->delete();

        History(Auth::user()->id,'بحذف المحادثة ');
        return redirect('admin/admin_conversations')->with('success','تم الحذف');
    }
}
