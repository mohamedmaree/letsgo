@extends('layouts.master')
@section('content')

@section('breadcrumb') 
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb wow fadeInUp">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">   الرئيسية  </a></li>
                        <li class="breadcrumb-item active" aria-current="page">  حساب جديد  </li>
                    </ol>
                </div>
@endsection
    
    <div class="login-form wow fadeInUp">
        <div class="container">
            <div class="box">
                <div class="log-head">
                    <div class="basic-head">
                        <h4>  حساب جديد  </h4>
                    </div>
                </div>
      @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif
                <div class="content">
                    <div class="row">
                        <div class="col-12  col-lg-6">
                            <div class="fields"> 
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-controls="user" aria-selected="true">
                                             مستخدم <span class="checkmark"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="provider-tab" data-toggle="tab" href="#provider" role="tab" aria-controls="provider" aria-selected="false">
                                              مقدم <span class="checkmark"></span>           
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                                        <form method="POST" action="{{ route('userRegister') }}"> 
                                           @csrf
                                            <div class="field"> 
                                                <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="الاسم ">
                                                <img src="{{url('images/i-user.png')}}" alt="icon" class="img-fluid ico-user">
                                            </div>
                                            <div class="field"> 
                                                <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder=" البريد الالكترونى ">
                                                <img src="{{url('images/i-envelope.png')}}" alt="icon" class="img-fluid ico-mail">
                                            </div>
                                            <div class="field"> 
                                                <input type="text" name="phone" value="{{old('phone')}}" class="form-control" placeholder="رقم الجوال ">
                                                <img src="{{url('images/i-phone.png')}}" alt="icon" class="img-fluid">
                                            </div>
                                            <div class="field"> 
                                                <input type="password" name="password" class="form-control" placeholder="كلمة المرور">
                                                <img src="{{url('images/i-lock.png')}}" alt="icon" class="img-fluid">
                                            </div>
                                            <button class="btn btn-green btn-animate" type="submit"> <span> تسجيل   </span></button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="provider" role="tabpanel" aria-labelledby="provider-tab">
                                        <form method="POST" action="{{ route('ProviderRegister') }}">
                                            @csrf
                                             <div class="field"> 
                                                <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="الاسم " required/>
                                                <img src="{{url('images/i-user.png')}}" alt="icon" class="img-fluid ico-user">
                                            </div>
                                            <div class="field"> 
                                                <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder=" البريد الالكترونى " required/>
                                                <img src="{{url('images/i-envelope.png')}}" alt="icon" class="img-fluid ico-mail">
                                            </div>
                                            <div class="field"> 
                                                <input type="text" name="phone" value="{{old('phone')}}" class="form-control" placeholder="رقم الجوال " required/>
                                                <img src="{{url('images/i-phone.png')}}" alt="icon" class="img-fluid ico-phone">
                                            </div>
                                            <div class="field adress-box"> 
                                                <input type="text" name="address" value="{{old('address')}}" id="address" class="form-control" placeholder="العنوان " required/>
                                                <img src="{{url('images/i-placeholder.png')}}" alt="icon" class="img-fluid ico-placeholder">
                                                <!-- <i class="icofont-google-map"></i> -->
                                            </div>
                                            <!-- <div class="map" id="map"> </div>
                                            <input type="hidden" name="lat" id="lat" >
                                            <input type="hidden" name="long" id="lng"> -->
<div class="form-group">
<div id="map" class="map" style="width: 100%;height:250px;"></div>
<input type="hidden" id="resultAddress"  class="form-control" placeholder=" تفاصيل العنوان " readonly>
<input type="hidden" id="lat" name="lat" value="{{old('lat')}}" class="form-control">
<input type="hidden" id="lng" name="long" value="{{old('long')}}" class="form-control">
</div> 



                                            <div class="input-collpse-box">
                                                <button class="btn btn-collapse" >
                                                    <img src="{{url('images/i-services.png')}}" alt="icon" class="img-fluid">
                                                    <span> نوع الخدمة  </span>
                                                    <i class="icofont-thin-down"></i>
                                                </button>
                                                <div class="collapse-box">
                                                    <div class="card-box">
                                                        @foreach($categories as $cat)
                                                         <label class="radio-container"> {{$cat->name}} 
                                                            <input type="radio" name="category_id" value="{{$cat->id}}" data-value="{{$cat->name}}">
                                                            <span class="checkmark"></span>
                                                        </label>

                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="field"> 
                                                <input type="password" name="password" class="form-control" placeholder="كلمة المرور">
                                                <img src="{{url('images/i-lock.png')}}" alt="icon" class="img-fluid ico-lock">
                                            </div>
                                            <button class="btn btn-green btn-animate" type="submit"> <span> تسجيل   </span></button>
                                        </form>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="link">
                                <h6> إذا كان لديك حساب بالفعل !  </h6>
                                <a href="{{route('login')}}">  إضغط هنا </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('map')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNm7VC4eQsCZcny5cVteIkg_SMJpc2G7Y&callback=initMap&language=ar"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script>
var map;
var marker;
var myLatlng ;//= new google.maps.LatLng(24.774265, 46.738586);
var geocoder = new google.maps.Geocoder();
var infowindow = new google.maps.InfoWindow();

function initMap(){

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} else {
   innerHTML = " حدث خطا أتناء تحديد الموقع ";
}
function showPosition(position) {
   maplat = position.coords.latitude;
   maplng = position.coords.longitude;
   myLatlng = { lat: maplat, lng: maplng };

var mapOptions = {
    zoom: 16,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

map = new google.maps.Map(document.getElementById("map"), mapOptions);

marker = new google.maps.Marker({
    map: map,
    position: myLatlng,
    draggable: true
});

geocoder.geocode({'latLng': myLatlng }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
            // $('#latitude,#longitude').show();
            $('#address').val(results[0].formatted_address);
            $('#resultAddress').val(results[0].formatted_address);
            $('#lat').val(marker.getPosition().lat());
            $('#lng').val(marker.getPosition().lng());
            infowindow.setContent(results[0].formatted_address);
            infowindow.open(map, marker);
        }
    }
});

google.maps.event.addListener(marker, 'dragend', function() {

    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                $('#address').val(results[0].formatted_address);
                $('#resultAddress').val(results[0].formatted_address);
                $('#lat').val(marker.getPosition().lat());
                $('#lng').val(marker.getPosition().lng());
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
            }
        }
    });
});

            }
}
google.maps.event.addDomListener(window, 'load', initMap);

</script>

@endsection
