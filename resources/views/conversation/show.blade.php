@extends('layouts.master')
    <style>
        .panel-body{
            height: 50vh;
            overflow-y: scroll;
        }
        .message{
            padding: 10pt;
            border-radius: 5pt;
            margin: 5pt;
        }
        .owner{
            background-color: #ccd7e0;
            float: right;
        }
        .not_owner{
            background-color: #eaeff2;
            float:left;
        }
    </style>
@section('content')
    <div class="container">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-8">
                            {{($conversation->firstuser->id==Auth::user()->id)?$conversation->seconduser->name:$conversation->firstuser->name}}
                        </div>
                    </div>
                </div>
                <div class="panel-body" id="panel-body">
                    @foreach($conversation->messages as $message)
                        <div class="row">
                            <div class="message {{ ($message->user_id!=Auth::user()->id)?'not_owner':'owner'}}">
                                {{$message->content}}<br/>
                                <b>{{$message->created_at->diffForHumans()}}</b>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                        <textarea id="msg" class="form-control" placeholder="Write your message"></textarea>
                        <input type="file" name="input-files" id="input-files"> 
                        <input type="hidden" id="csrf_token_input" value="{{csrf_token()}}"/>
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <button class="btn btn-primary btn-block" onclick="sendFile()">Send File</button>
                                <button class="btn btn-primary btn-block" onclick="button_send_msg()">Send message</button>
                            </div>
                        </div>
                        <div class="row">
                        <input type="text" id="lat" value=""/>
                        <input type="text" id="long" value=""/>
                         <button class="btn btn-primary btn-block" onclick="updatelocation()">updatelocation</button>
                        <br/>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
    <script>
        var socket = io.connect('http://ezzk.4hoste.com:8898');
        console.log(socket);
        // socket.emit('adduser', {'client':{{Auth::user()->id}},'conversation':{{$conversation->id}}});
        socket.emit('addtracker', {'tracker_id':10,'user_id': 14});
        socket.on('trackorder', function (data) {
            alert('trackorder')
            $('#panel-body').append(
                    '<div class="row">'+
                    '<div class="message not_owner">'+
                    data.lat+'----'+data.lng+'<br/>'+
                    '</div>'+
                    '</div>');

            scrollToEnd();
         });        
        
    </script>
    <script>
        $(document).ready(function(){
            scrollToEnd();

            $(document).keypress(function(e) {
                if(e.which == 13) {
                    var msg = $('#msg').val();
                    $('#msg').val('');//reset
                    send_msg(msg);
                }
            });
        });

        function button_send_msg(){
            var msg = $('#msg').val();
            $('#msg').val('');//reset
            send_msg(msg);
        }

        function updatelocation(){
            var lat = $('#lat').val();
            var lng = $('#long').val();
            socket.emit('updatelocation', {'user_id':14,'lat':lat,'lng':lng });
        }

        function send_msg(msg){
            <?php $receiver_id = (Auth::user()->id == $conversation->firstuser->id)? $conversation->seconduser->id:$conversation->firstuser->id; ?>
            socket.emit('sendmessage', {'sender_id':{{Auth::user()->id}},'receiver_id':{{$receiver_id}},'conversation_id':{{$conversation->id}},'content':msg,'type':'text' });
        }

        function scrollToEnd(){
            var d = $('#panel-body');
            d.scrollTop(d.prop("scrollHeight"));
        }

    </script>


@endsection

