<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield("title")</title>

	<!-- Global stylesheets -->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/icons/icomoon/styles.css')}}"  rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/bootstrap.css')}}"          rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/core.css')}}"               rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/components.css')}}"         rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/colors.css')}}"             rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/custome.css')}}"             rel="stylesheet" type="text/css">	
	<link href="{{asset('dashboard/css/extras/animate.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('dashboard/checkbox-radio-master/dist/css/checkbox_radio_img_sprite.css')}}">

	@yield('style')
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{asset('dashboard/js/plugins/loaders/pace.min.js')}}"></script>
	<script src="{{asset('dashboard/js/core/libraries/jquery.min.js')}}"></script>
	<script src="{{asset('dashboard/js/core/libraries/bootstrap.min.js')}}"></script>
	<script src="{{asset('dashboard/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.all.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{asset('dashboard/js/core/app.js')}}"></script>
	<script src="{{asset('dashboard/js/pages/animations_css3.js')}}"></script>
    </head>

	@yield('script')
<body>
	@include('dashboard.parts.navbar')
	<!-- Page container -->
	<div class="page-container">
			<!-- Page content -->
		<div class="page-content">
				@include('dashboard.parts.sidebar')

			<div class="content-wrapper">
				<!-- page header -->
				<div class="page-header">
				        <div class="page-header-content">

				        	<!-- company logo -->
				            <div class="page-title">
				                <h4><img src="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}"></h4>
				            </div>
				            <!-- /company logo -->
				            
				            <!-- speed access -->
				            <div class="heading-elements">
				                <div class="heading-btn-group">
				                    <a href="{{route('dashboard')}}" class="btn btn-link btn-float has-text"><i class="icon-home4 text-primary"></i> <span>الرئيسيه</span></a>
				                    <a href="{{route('setting')}}" class="btn btn-link btn-float has-text"><i class="icon-cog text-primary"></i> <span>الاعدادات</span></a>
				                    <a href="{{route('logout')}}" class="btn btn-link btn-float has-text" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="icon-switch text-primary"></i><span>خروج</span></a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
				                </div>
				            </div>
				            <!-- /speed access -->
				        </div>

				        <div class="breadcrumb-line">
				            <ul class="breadcrumb">
				                <li><a href="{{route('dashboard')}}"><i class="icon-home2 position-left"></i> الرئيسيه / </a> {{currentRoute()}}</li>
				            </ul>

				            <ul class="breadcrumb-elements">
				                <li class="dropdown">
				                    <a href="{{route('setting')}}" >
				                        <i class="icon-gear position-left"></i>
				                        الاعدادات
				                    </a>
				                </li>
				            </ul>

				        </div>
				    </div>
				    <!-- /page header -->
				@include('dashboard.parts.alert')

				<!-- loading -->
				<div class="content loadingPage">
					<div class="loading" style="height: 400px;width: 100%;background: #fff;text-align: center;"> 
					    <div class="con" style="padding-top: 80px">
				            <h4><img src="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}" style="width: 290px ;height: 60px"></h4>
				            <h3>انتظر من فضلك</h3>
				            <i class="fa fa-cog fa-spin fa-3x fa-fw" style="color: #03A9F4"></i>
				        </div>
					</div>
				</div>
				<!-- /loading -->

				<!-- content -->
				<div class="content main" style="display: none;">
					@yield('content')
				</div>
				<!-- /content -->
			</div>

		</div>
		<!-- /Page content -->
	</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
    <script>
        var socket = io.connect('https://letsgo-app.net:4640');
        socket.on('newAskForHelp', function (data) {
                    $('<audio id="chatAudio"><source src="{{url('dashboard/notify.mp3')}}" type="audio/mpeg"></audio>').appendTo('body');
                    $('#chatAudio')[0].play();
                    alert('هناك طلب استغاثة.');
         });                      
    </script>		
<script>
	//images
	jQuery(function($){
		var fileDiv = document.getElementById("upload");
	if(fileDiv != null){
		var fileInput = document.getElementById("upload-image");
		console.log(fileInput);
		fileInput.addEventListener("change",function(e){
		  var files = this.files
		  showThumbnail(files)
		},false)

		fileDiv.addEventListener("click",function(e){
		  $(fileInput).show().focus().click().hide();
		  e.preventDefault();
		},false)

		fileDiv.addEventListener("dragenter",function(e){
		  e.stopPropagation();
		  e.preventDefault();
		},false);

		fileDiv.addEventListener("dragover",function(e){
		  e.stopPropagation();
		  e.preventDefault();
		},false);

		fileDiv.addEventListener("drop",function(e){
		  e.stopPropagation();
		  e.preventDefault();

		  var dt = e.dataTransfer;
		  var files = dt.files;

		  showThumbnail(files)
		},false);
    }

	function showThumbnail(files){
	  for(var i=0;i<files.length;i++){
	    var file = files[i]
	    var imageType = /image.*/
	    if(!file.type.match(imageType)){
	      console.log("Not an Image");
	      continue;
	    }

	    var image = document.createElement("img");
	    // image.classList.add("")
	    var thumbnail = document.getElementById("thumbnail");
	    image.file = file;
	    thumbnail.appendChild(image)

	    var reader = new FileReader()
	    reader.onload = (function(aImg){
	      return function(e){
	        aImg.src = e.target.result;
	      };
	    }(image))
	    var ret = reader.readAsDataURL(file);
	    var canvas = document.createElement("canvas");
	    ctx = canvas.getContext("2d");
	    image.onload= function(){
	      ctx.drawImage(image,100,100)
	    }
	  }
	}
	});

	//reload page
	$('.reload').click(function() {
   		 location.reload();
	});
	
	$(window).load(function() {
		// Animate loader off screen
		$(".loadingPage").fadeOut("slow");;
		$('.main').show();
	});


	//ADD IMAGE
    $('.image-uploader').change(function (event){
        $(this).parents('.images-upload-block').append('<div class="uploaded-block"><img src="'+ URL.createObjectURL(event.target.files[0]) +'"><button class="close">&times;</button></div>');
    });

    //Remove image
    $('.images-upload-block').on('click', '.close',function (){
        $(this).parents('.uploaded-block').remove();
    });


	$('.photo').on('click', function(){
		$(this).parent().append('<div class="img_appendbox"><img class="new-img"> '+' <span class="close-img">&times;</span></div>');
	})

	var input_file = $('.photo').parent().find('input');

	input_file.change(function () {
        var input = (this);
        var image = $(this).siblings('.img_appendbox').find('.new-img');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                image.attr('src', e.target.result);
                console.log(this);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $('.close-img').on('click', function (){
        $(this).closest('.img_appendbox').remove();
    });


	$('.cover').on('click', function(){
		$(this).parent().append('<div class="img_appendbox"><img class="new-img"> '+' <span class="close-img">&times;</span></div>');
	})

	var input_file = $('.cover').parent().find('input');
	input_file.change(function () {
        var input = (this);
        var image = $(this).siblings('.img_appendbox').find('.new-img');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                image.attr('src', e.target.result);
                console.log(this);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $('.close-img').on('click', function (){
        $(this).closest('.img_appendbox').remove();
    });

    // $('.img_appendbox').on('click' , function (){
    //     // $(this).siblings('.new-img').remove();
    //     alert(this.html());
    // });

    
	</script>
	<script src="{{asset('dashboard/plugins/materialize/js/materialize.min.js')}}"></script>
	<script>
		socket.on('offline_message', (message)=> {
			console.log('New Offline message');
			console.log("message", message);
			@if(!Request::is('admin/admin_conversations*'))
			setTimeout(function(){ Materialize.toast('لديك رسالة جديدة من اللايف شات', 7000) }, 4000);
			console.log('not here');
			@else
			console.log('you are in admin conversation');
			@endif

		});

	</script>
	@yield('after-scripts')
</body>
</html>