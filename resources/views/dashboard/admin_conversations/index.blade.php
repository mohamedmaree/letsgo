@extends('dashboard.layout.master')
@section('style')
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
    <style>
        .rebtn{background:url( {{ asset('assets/web/reload.gif') }} ) no-repeat center center !important;}
        /*.chat-application .chats .chat-left .chat-body { margin-right: 0; }*/
        /*.chat-application .chats .chat-left .chat-content {margin: 0 20px 0 0;}*/
        .chat-application .chats .chat-body .chat-content { max-width: 300px; max-height: 300px; }
        .chat-application .chats .chat-body .chat-content img { width: 100% }
        .chat-application .chat-app-window{height:456px; overflow: auto}
        .chat-application .users-list-padding{padding: 10px !important;}
        .chat-app-input{display: flex;align-items: center;}
        .input-field{width: 100%;position: relative}
        .input-field .img-up{
            position: absolute;
            top: 0;
            left: 12px;
            width: 25px;
            height: 25px;
            bottom: 0;
            margin: auto;
            text-align: center;
        }
        .list-group{
            height: 460px !important;
            overflow: auto !important;
        }
        .list-group::-webkit-scrollbar {
            width: 0;
        }
        .chat-app-window p.pikeconv{
            height: 100%;
            line-height: 400px;
            text-align: center;
            font-size: 17px;
        }
        .roomChat{
            background: rgba(211, 221, 221, 0.37);
            padding: 0 8px;
            border-radius: 5px;
            margin: 5px 0;
            transition: .4s all ease;
        }
        .roomChat:hover{
            background: rgba(127, 140, 135, 0.37);
        }
        .roomChat .media-left span img{
            margin-top: 10px;
        }
        .chat-application .chat-fixed-search {
            width: 100%;
            border-bottom:0;
        }
        .list-group {
            border:0;
        }
        .chat-application .chat-app-form {
            top: -56px;
            padding: 10px;
        }
        .chatBody{
            height: 511px;
            background-color: #fff;
        }
        .chat-application .chat-app-window::-webkit-scrollbar {
            width: 0;
            height: 0;
        }
        .input-field {
            margin: 0 !important;
        }
        .form-group {
            margin: 0 !important;
        }
        .chatHeader{
            background-color: #fff;
            padding: 1px;
            margin: 5px 0;
            display: flex;
            justify-content: center;
            border-radius: 5px;
        }
        .chat-application .form-control-position {
            position: absolute;
            left: 11px;
            top: 9px;
            font-size: 18px;
            color: #f2ce21;
        }
        .chat-application .form-control-position.control-position-right {
            left: -5px;
            top: -9px;
        }
        .chat-application .unread-message {
            background: rgba(127, 140, 135, 0.25);
        }
        .chat-application .unread-message:hover{
            background: rgba(127, 140, 135, 0.37);
        }
    </style>
    <link href="{{ asset('css/fancy.css') }}" rel="stylesheet">
@stop
@section('title')
    المحادثات
@endsection
@section('content')
    <div class="row">
{{--        @if($conversation == 0 )--}}
{{--        <div class="col-xs-12">--}}
{{--            <div class="chatHeader">--}}
{{--                <h4>محادثات العملاء</h4>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @endif--}}


        <div class="col-sm-4 col-xs-12">
            <div style="width: 100% ;background-color: #fff" class="">
                <div class="sidebar-content card d-none d-lg-block">
                    <div class="card-body chat-fixed-search">
                        <fieldset class="form-group position-relative has-icon-left m-0">
                            <input style="height: 45px;border: 1px solid #24a2c7;border-radius: 5px;margin: 0 0 6px 0;" id="search-input" type="text" class="form-control" placeholder="ابحث عن مستخدم">
                            <div class="form-control-position">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </div>
                        </fieldset>
                    </div>
                    <div id="users-list" class="list-group position-relative">
                        <div class="users-list-padding media-list">
                            <!-- List Of users -->
                            <!-- Data Loader -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-xs-12">
            <div class="chatBody">
                <section class="chat-app-window">
                    @if($conversation == 0 )
                        <p class="pikeconv">
                            <i class="glyphicon glyphicon-comment"></i>
                            اختر محادثه
                        </p>
                    @endif
                    <div class="chats">
                        @foreach($messages as $message)
                            @if($message->user_id == 0)
                                <div class="chat chat-left">
                                    <div class="chat-avatar">
                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title=""
                                           data-original-title="">
                                            <img alt="avatar" src="{{ asset('dashboard/uploads/setting/site_logo/logo.png') }}" width="25"/>
                                        </a>
                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
                                            @if($message->type=='image')
                                                <a data-fancybox="gallery" href="{{asset('chatuploads/'.$message->content)}}">
                                                    <img style="width: 200px;height: 200px" src="{{asset('chatuploads/'.$message->content)}}" >
                                                </a>
                                            @else
                                                <p>
                                                    {{$message->content}}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="chat">
                                    <div class="chat-avatar">
                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title=""
                                           data-original-title="">
                                            <img alt="avatar" src="{{ url('img/user/'.$message->user->avatar) }}" width="25"/>
                                        </a>
                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
                                            @if($message->type=='image')
                                                <a data-fancybox="gallery" href="{{asset('chatuploads/'.$message->content)}}">
                                                    <img style="width: 200px;height: 200px" src="{{asset('chatuploads/'.$message->content)}}" >
                                                </a>
                                            @else
                                                <p>{{$message->content}}</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </section>
            </div>
            <section class="chat-app-form @if($conversation == 0 ) hidden @endif ">
                <form class="chat-app-input d-flex">
                    <fieldset class="form-group position-relative col-10 m-0 input-field">
                        <input id="messageInput" type="text" class="form-control" autofocus placeholder="أكتب رسالتك هنا">
{{--                        <div class="img-up">--}}
{{--                            <label style="position: relative" for="add-imgs-to-conv">--}}
{{--                                <div class="form-control-position control-position-right">--}}
{{--                                    <div id="image-preview">--}}

{{--                                    </div>--}}
{{--                                    <input style="width: 100%; height: 100%;opacity: 0" type="file" name="image" id="add-imgs-to-conv" class="d-none btn-icon image">--}}
{{--                                    <i class="ft-image"></i>--}}
{{--                                </div>--}}
{{--                                <i style="font-size: 21px;margin-top: 3px; position: absolute;color: #00bcd4;top: -9px" class="icon-camera"></i>--}}
{{--                            </label>--}}
{{--                        </div>--}}
                    </fieldset>
                    <fieldset class="form-group position-relative has-icon-left col-2 m-0">
                        <a onclick="sendMessageBtn()" class="btn btn-info">
                            <i class="la la-paper-plane-o d-lg-none"></i>
                            <span class="d-none d-lg-block" >إرسال</span>
                        </a>
                    </fieldset>
                </form>
            </section>
        </div>
    </div>

@endsection
    <!-- javascript -->
@section('script')
@endsection

@section('after-scripts')
    <script type="text/javascript" src="{{asset('js/fancy.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("body").addClass('chat-application');
        });
    </script>
    <!-- Start chat -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        <!-- Request Errors -->
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
    <!-- End chat -->

    <script>
        $(document).ready(function() {
            console.log('ready')
            $(document).on('click', '.conversation-delete', function() {
                // $('.conversation-delete').on('click', function (e) {
                console.log('delete');
                var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
                if(result == false){
                    e.preventDefault();
                }
            });
        })
        // console.log(socket);
        // Connect to socket
        var client = 0,
            userId = 1,
            conversation = {{$conversation}},
            content = "",
            adminAvatar = "{{ asset('dashboard/uploads/setting/site_logo/logo.png') }}",
            avatar = "{{ $avatar }}",
            receiver_id = {{$receiver_id}};


        var ENDPOINT = "{{ url('/') }}",
            page = 1,
            url,
            q = '';

        // console.log('client', client, 'conversation', conversation, 'receiver_id', receiver_id);
        socket.emit('adduser', {'client':client,'conversation':conversation });
        (function() {
            // console.log({'client':client,'conversation':conversation });

            // Retrieve Message => admin_message
            socket.on('admin_message', function(message) {
                // Tune
                $('<audio id="chatAudio"><source src="{{asset('assets/notify.mp3')}}" type="audio/mpeg"></audio>').appendTo('body');
                $("#chatAudio")[0].play();
                console.log('New Online Message');
                append_received_message(message);

                scrollToEnd();

                // var messageCount = $('message-count[id="'+message.sender_id+'"]').html();
                // console.log( messageCount) ;

            });

            socket.on('offline_message', (message)=> {
                console.log('New Offline message');
                console.log("message", message);

                prepend_user_section(message);
            });

            socket.on('disconnect', () => {
                console.log('disconnect');
            });
        })();


        function sendMessageBtn() {
            var image = '';
            var video = '';
            if (typeof $('#add-imgs-to-conv')[0] !== 'undefined'){
                image = $('#add-imgs-to-conv')[0].files[0];
            }
            if(image) {
                uploadImage(image);
            }
            var message = $('#messageInput').val();
            $('#messageInput').val('');//reset
            if($.trim(message) != ''){
                sendMessage(message,'text');
            }
            scrollToEnd();
        }

        // Send message
        function sendMessage(message,type){
            if(type == 'text'){
                console.log({'sender_id':client, 'receiver_id':receiver_id, 'conversation_id':conversation, 'content':message, 'type':'text'});
                console.log(client, receiver_id, conversation, message);
                socket.emit('send_admin_message', {'sender_id':client, 'receiver_id':receiver_id, 'conversation_id':conversation, 'content':message, 'type':'text'});
                console.log('After emit');
                $('.chats').append('<div class="chat chat-left">\n' +
                    '<div class="chat-avatar">\n' +
                    '                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title=""\n' +
                    '                                           data-original-title="">\n' +
                    '                                            <img alt="avatar" width="25" src="'+ adminAvatar +'">' +
                    '                                        </a>\n' +
                    '                                    </div>'+
                    '                            <div class="chat-body">\n' +
                    '                                <div class="chat-content">\n'
                    +
                    '<p>' +message + '</p>'
                    +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>');
                $(".image_div").remove();

            }else if(type == 'image'){
                socket.emit('send_admin_message', {'sender_id':client, 'receiver_id':receiver_id, 'conversation_id':conversation, 'content':message, 'type':'image'});
            }

            scrollToEnd();
        }

        $(document).ready(function (e) {
            scrollToEnd();
            $("body").addClass('chat-application');

            $(document).keypress(function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    var image = '';
                    if (typeof $('#add-imgs-to-conv')[0] !== 'undefined'){
                        image = $('#add-imgs-to-conv')[0].files[0];
                    }

                    if(image){
                        uploadImage(image);
                    }

                    var message = $('#messageInput').val();
                    $('#messageInput').val('');//reset
                    if($.trim(message) != ''){
                        sendMessage(message,'text');
                    }
                }
            });
            scrollToEnd();
        })

        function uploadImage(image) {
            $('#messageInput').addClass("reload-btn");
            // $('#msg').attr("disabled",true);
            $('#add-imgs-to-conv').val('');//reset
            var formData = new FormData();
            formData.append('image',image);
            console.log(formData);

            $.ajax({
                url:'<?=route("uploadFile");?>',
                type:"POST",
                data:formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function(data){
                    // console.log(data);
                    if(data.value == '0'){
                        swal("Error!", "data.data.msg");
                        // alert(data.data.msg);
                    }else{
                        $('.chats').append('<div class="chat chat-left">\n' +
                            '                            <div class="chat-body">\n' +
                            '                                <div class="chat-content">\n' +
                            '<a data-fancybox="gallery" href="{{ asset('chatuploads/')}}'+ '/' + data.data.name + '">\n' +
                            '   <img style="width: 200px;height: 200px" src="{{asset('chatuploads/')}}'+'/'+data.data.name+'" width="100" alt="">\n' +
                            '</a>'
                            +
                            '                                </div>\n' +
                            '                            </div>\n' +
                            '                        </div>');
                        $(".image_div").remove();

                    }
                    $(".chat-application .chat-app-window").animate({scrollTop: $('.chat-application .chat-app-window').prop("scrollHeight")}, 500);
                    $('#messageInput').removeClass("reload-btn");
                    // $('#msg').attr("disabled",false);
                    $('#messageInput').attr('placeholder', "اكتب رسالتك هنا");

                    sendMessage(data.data.name,'image');
                    scrollToEnd();
                }
            })
            scrollToEnd();
        }

        function scrollToEnd(){
            var chatContent = $('.chat-application .chat-app-window');
            chatContent.scrollTop(chatContent.prop("scrollHeight"));
        }

        // Load single conversation
        $(document).on('click', '.user-section', function (e) {
            //////// *******************
            $(".pikeconv").remove();
            // console.log('user-section');
            // $(this).addClass('hidden');
            $('.chats').addClass("reload-btn");
            $('.chats').empty();
            // socket.emit('leave_conversation', {'user_id':client,'conversation_id':conversation });
            // console.log('Before: '+client, conversation);
            userId = $(this).data('user-id');
            remove_user_section(userId);
            console.log(userId, '{{ route('adminConversations') }}/' + userId);

            $.ajax({
                url:'{{ route('adminConversations') }}/' + userId,
                type:"GET",
                data: {user_id: userId},
                contentType: false,
                processData: false,
                cache: false,
                success: function(response) {
                    console.log('Before :- client', client, 'conversation', conversation, 'receiver_id', receiver_id);
                    conversation = response.data.conversation;
                    receiver_id = response.data.receiver_id;
                    avatar = response.data.avatar;
                    socket.emit('adduser', {'client': client, 'conversation': conversation});
                    console.log('After :- client', client, 'conversation', conversation, 'receiver_id', receiver_id);

                    // Livewire.emit('changeReceiver', receiver_id);

                    $(".seclection").addClass('hidden');
                    $(".chat-app-form").removeClass('hidden');
                    $('.chats').removeClass("reload-btn");
                    // console.log(response);
                    let messages = response.data['messages'];
                    // console.log(avatar);
                    load_conversation(messages);
                    scrollToEnd();
                }
            })
        });

        function append_received_message(message) {
            if(message.type == 'image') {
                content =
                    '<a data-fancybox="gallery" href="{{asset('chatuploads/')}}'+ '/' + message.content + '">\n' +
                    '  <img src="{{asset('chatuploads/')}}'+'/'+message.content+'" width="100" alt="">\n' +
                    '</a>';
            } else {
                content = '<p>'+ message.content +'</p>';
            }

            $('.chats').append('<div class="chat">\n' +
                '                            <div class="chat-avatar">\n' +
                '                                <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title=""\n' +
                '                                   data-original-title="">\n' +
                '                                    <img alt="avatar" src="'+ avatar +'"\n' +
                '                                    width="25"/>\n' +
                '                                </a>\n' +
                '                            </div>\n' +
                '                            <div class="chat-body">\n' +
                '                                <div class="chat-content">\n' + content +
                '                                </div>\n' +
                '                            </div>\n' +
                '                        </div>');
            $(".image_div").remove();
        }

        function load_conversation(messages) {
            messages.forEach(message => {
                let msg = message['content'];
                if (message['type'] == 'image') {
                    msg = `
                            <a data-fancybox="gallery" href="{{asset('chatuploads/')}}/`+ message['content'] +`">
                                <img style="width: 200px;height: 200px" src="{{asset('chatuploads/')}}/`+ message['content'] +`" >
                            </a>`
                } else {
                    msg = `
                            <p>
                                  `+ message['content'] +`
                            </p>`;
                }
                if (message['user_id'] == '0'){
                    // console.log(message);
                    $(".chats").append(`
                            <div class="chat chat-left">
                                    <div class="chat-avatar">
                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title=""
                                           data-original-title="">
                                            <img alt="avatar" src="{{ asset('dashboard/uploads/setting/site_logo/logo.png') }}" width="25"
                                            />
                                        </a>
                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">

                                        `+ msg +`


                                        </div>
                                    </div>
                                </div>
                                `);
                } else {
                    // console.log(message);
                    $(".chats").append(`
                            <div class="chat">
                                    <div class="chat-avatar">
                                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title=""
                                           data-original-title="">
                                            <img alt="avatar" src="`+ avatar +`" width="25"
                                            />
                                        </a>
                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
                                        `+ msg +`

                                        </div>
                                    </div>
                                </div>
                                `);

                }
                // console.log(message['user_id']);
                // console.log(response.data['avatar']);
            });

        }

        function remove_user_section(user_id) {
            var userSection = $('.user-section[id=user-section-'+userId+']');
            // console.log(userSection);
            // userSection.remove();
        }

        // Image Preview
        $('#add-imgs-to-conv').on( "change" , function () {
            $('#image-preview').append('' +
                '<div class="image_div">' +
                '<img style="width: 35px; height: 35px;margin-left: 65px;position: absolute;right: 0;top: -5px; z-index: 9" src="' + URL.createObjectURL(event.target.files[0]) + '">' +
                '</div>' +
                '');
        });


        infinteLoadMore(page);
        console.log('Init');

        $('#users-list').on('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                if($(".auto-load").get().length == 0) {
                    page++;
                    infinteLoadMore(page);
                    console.log($(".auto-load").get());
                    console.log($(".auto-load").get().length);
                    $(".users-list-padding").append(`
                        <div style="display: flex;justify-content: center" class="auto-load text-center">
                        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                        <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                </svg>
                </div>`);
                }

            }
        });

        $('#search-input').keyup(function() {
            // console.log($(this).val());

            q = $(this).val();
            page = 1;
            console.log(q, url)
            searchUsers(page, q);
        });

        // console.log(q, page);
        function infinteLoadMore(page) {
            if (q == '') {
                url = ENDPOINT + "/admin/users?page=" + page ;
            } else {
                url = ENDPOINT + "/admin/users?page=" + page + "&q=" + q;
                console.log('has val');
            }
            // console.log(ENDPOINT);
            // console.log(ENDPOINT + "/admin/users?page=" + page);
            console.log(url);
            $.ajax({
                url: url,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    // $('.auto-load').show();
                }
            })
                .done(function (response) {
                    if (response == '') {
                        $('.auto-load').addClass('hidden');
                        return;
                    }
                    $(".users-list-padding").append(response);
                    // console.log(response)
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
        }

        function searchUsers(page, q) {
            page = 1;
            // console.log('ENDPOINT', ENDPOINT, q, page);
            if (q == '' || q == 'undefined') {
                url = ENDPOINT + "/admin/users?page=" + page ;
            } else if (page == '' || page == 'undefined') {
                url = ENDPOINT + "/admin/users?page=" + 1 ;
            }
            else {
                url = ENDPOINT + "/admin/users?page=" + page + "&q=" + q;
                // console.log('has val');
            }
            // console.log(ENDPOINT + "/admin/users?page=" + page);
            // console.log('url in search : ', url, 'q : ', q);
            $.ajax({
                url: url,
                datatype: "html",
                // data: {
                //     q: q
                // },
                type: "get",
                success: function(response) {
                    // console.log(response);
                    $(".users-list-padding").html(response);
                    console.log('After Send');
                    if (response == '') {
                        $(".users-list-padding").html(`
                        <a id="" class="media border-0 bg-blue-grey bg-lighten-5 user-section no-data">
                            <div class="media-body">
                                <h6 class="list-group-item-heading">لا يوجد بيانات </h6>
                            </div>
                        </a>
                        `);
                        console.log('empty');
                    }
                    // console.log(response);
                },
            }).done(function (response) {
                if (response.length == 0) {
                    // $('.auto-load').html("We don't have more data to display :(");
                    return;
                }
                // $('.auto-load').hide();
                // console.log('done');
                $(".users-list-padding").html(response);
                // console.log(response)
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
        }

        function prepend_user_section(message) {
            // searchUsers(page, '');
            var userSection = $('#user-section-'+message.sender_id),
                messageCountSection = $('#message-count-'+message.sender_id);

            searchUsers(page, q);
        }

    </script>
@endsection

