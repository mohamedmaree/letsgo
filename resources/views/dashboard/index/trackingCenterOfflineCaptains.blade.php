@extends('dashboard.layout.master')
    @section('title')
    مركز عمليات التتبع 
    @endsection
@section('content')
    <style>
        .gm-style .gm-style-iw{
            padding-bottom: 12px!important;
            padding-left: 12px!important;
        }
        .first,.first.panel-flat > .panel-heading,.gm-style .gm-style-iw,
        .gm-style .gm-style-iw-d
        {
            background: #111;
            color: #fff;
        }
        .gm-style .gm-style-iw-t::after {
            background: linear-gradient(45deg, rgba(17, 17, 17, 1) 50%, rgba(17, 17, 17, 0) 51%, rgba(17, 17, 17, 0) 100%);
        }
        .poi-info-window div, .poi-info-window a{
            color:#fff;
            background: #111    ;
        }
        .gm-style .gm-style-iw-d{
            overflow: auto!important;
        }
    </style>
<div class="panel panel-flat first">
    <div class="panel-heading">
            <div class="row">
               <div class="col-lg-12">
                        <div class="card visitors-card">
                            <div class="card-content">
                              <h2 style="margin-right: 38%;">مركز تتبع القادة </h2>
                                <div class="map" id="map" style="with:90%;height:500px;"> 
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="col-lg-4">
                    <div class="card visitors-card" style="background-color: #333;padding: 15px">
                        <div class="card-content">
                            <div style="display: flex; align-items: center;flex-wrap: wrap">
                                <a href="{{url('admin/trackingCenterOfflineCaptains')}}">
                                  <div style="width: 90px;text-align: center;border-radius: 5px;padding: 5px;background-color:#111;margin-inline-end: 5px;margin-bottom: 10px">
                                      <img src="{{url('img/offline.png')}}" style="width: 40px;height: 20px;">
                                      <span style="display: block">غير متاح ({{$num_offline}})</span>
                                  </div>
                                </a>
                                <a href="{{url('admin/trackingCenterAvailableCaptains')}}">
                                  <div style="width: 90px;text-align: center;border-radius: 5px;padding: 5px;background-color:#111;margin-inline-end: 5px;margin-bottom: 10px">
                                      <img src="{{url('img/available.png')}}" style="width: 40px;height: 20px;">
                                      <span style="display: block"> متاح ({{$num_available}})</span>
                                  </div>
                                </a>
                                <a href="{{url('admin/trackingCenterOnjobCaptains')}}">
                                  <div style="width: 90px;text-align: center;border-radius: 5px;padding: 5px;background-color:#111;margin-inline-end: 5px;margin-bottom: 10px">
                                      <img src="{{url('img/onjob.png')}}" style="width: 40px;height: 20px;">
                                      <span style="display: block"> فى رحلة ({{$num_onjob}})</span>
                                  </div>
                                </a>
                                <a href="{{url('admin/trackingCenterTocustomerCaptains')}}">
                                  <div style="width: 90px;text-align: center;border-radius: 5px;padding: 5px;background-color:#111;margin-inline-end: 5px;margin-bottom: 10px">
                                      <img src="{{url('img/tocustomer.png')}}" style="width: 40px;height: 20px;">
                                      <span style="display: block">الي العميل ({{$num_tocustomer}})</span>
                                  </div>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div style="background-color: #333;padding: 15px ;">
                        <div class="card visitors-card">
                            <div class="card-content">
                                <div style="display: grid;">
                                    <form action="{{url('admin/searchDriver')}}" method="POST" >
                                        {{csrf_field()}}
                                        <label style="text-align: center;display: block;font-size: 20px">البحث</label>
                                        <div style="display: flex;align-items: center;justify-content: center;margin-bottom: 15px">
                                            <div style="margin-inline-end: 10px;display: flex;align-items: center">
                                                <input style="margin-inline-end: 5px" type="radio" name="searchby" {{(isset($searchby))? (($searchby == 'order_id')? 'checked': '' ): ''}} value="order_id" required> رقم الطلب
                                            </div>
                                            <div style="margin-inline-end: 10px;display: flex;align-items: center">
                                                <input style="margin-inline-end: 5px" type="radio" name="searchby" {{(isset($searchby))? (($searchby == 'phone')? 'checked': '' ): ''}} value="phone" required> هاتف القائد
                                            </div>
                                            <div style="margin-inline-end: 10px;display: flex;align-items: center">
                                                <input type="radio" style="margin-inline-end: 5px" name="searchby" {{(isset($searchby))? (($searchby == 'pin_code')? 'checked': '' ): ''}} value="pin_code" required> كود القائد
                                            </div>
                                            <div style="display: flex;align-items: center">
                                                <input type="radio" style="margin-inline-end: 5px" name="searchby" {{(isset($searchby))? (($searchby == 'ticket_id')? 'checked': '' ): ''}} value="ticket_id" required> رقم الشكوي
                                            </div>
                                        </div>
                                        <div>
                                            <input type="text" name="search" value="{{($search)??''}}" style="background: #111;border: none;border-radius: 0;color:#fff" class="form-control" required>
                                        </div>
                                        <button style="display: block;margin: 15px auto 0" type="submit" class="btn btn-primary">بحث</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card visitors-card">
                            <div class="card-content">
                                <div style="display: grid;">
                                    @if($captain)
                                        <span>الاسم : <a href="{{url('admin/userProfile/'.$captain->id)}}">{{$captain->name}} </a></span>
                                        <span>كود القائد : <a href="{{url('admin/userProfile/'.$captain->id)}}">{{$captain->pin_code}} </a></span>
                                        <span>رقم الهاتف : <a href="{{url('admin/userProfile/'.$captain->id)}}">{{$captain->phonekey.$captain->phone}} </a></span>
                                    @endif
                                    @if($order)
                                        <span>رقم الرحلة : <a href="{{url('admin/showOrder/'.$order->id)}}">{{$order->id}} </a></span>
                                        <span>نقطة البداية : <a href="{{url('admin/showOrder/'.$order->id)}}">{{$order->start_address}} </a></span>
                                        <span>نقطة الوصول : <a href="{{url('admin/showOrder/'.$order->id)}}">{{$order->end_address}} </a></span>
                                        @if($order->start_journey_time)
                                            <span>وقت الانطلاق : {{date('Y-m-d H:i',strtotime($order->start_journey_time))}}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
    </div>  
</div>  

    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<script type="text/javascript">
//start map
        let map;
        let markers = [];

            function initMap() {
            // if (navigator.geolocation) {
            //     navigator.geolocation.getCurrentPosition(showPosition);
            // } else {
            //    innerHTML = " حدث خطا أتناء تحديد الموقع ";
            // }

            //     function showPosition(position) {
                  @if($captain)
                    const directionsService = new google.maps.DirectionsService();
                    const directionsRenderer = new google.maps.DirectionsRenderer({preserveViewport: true});
                    maplat = <?=$captain->lat;?>;
                    maplng = <?=$captain->long;?>;
                  @else
                    maplat = 24.774265;
                    maplng = 46.738586;
                  @endif
                   

                    var myLatLng = { lat: maplat, lng: maplng };
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 11,
                        center: myLatLng ,
                        // disableDefaultUI: false,
                        mapTypeId: google.maps.MapTypeId.TERRAIN,
                    });
                    setMarkers(map);
                    @if($order)
                      directionsRenderer.setMap(map);
                      calculateAndDisplayRoute(directionsService, directionsRenderer);
                    @endif
                // }
            }
        
function calculateAndDisplayRoute(directionsService, directionsRenderer) {
  // const waypts = [];
  // const checkboxArray = ['montreal, quebec','toronto, ont','chicago, il'];

  // for (let i = 0; i < checkboxArray.length; i++) {
  //   if (checkboxArray) {
  //     waypts.push({
  //       location: checkboxArray[i].value,
  //       stopover: true
  //     });
  //   }
  // }
  @if($order)
    var first = new google.maps.LatLng(<?=$order->current_lat;?>, <?=$order->current_long;?>);

  directionsService.route(
    {
      origin: '<?=$order->start_address;?>',
      destination: '<?=$order->end_address;?>',
      waypoints: [],
      // waypoints: [{location: first, stopover: false}],
      optimizeWaypoints: true,
      travelMode: google.maps.TravelMode.DRIVING,
    },
    (response, status) => {
      if (status === "OK") {
        directionsRenderer.setDirections(response);
        const route = response.routes[0];

      } else {
        window.alert("Directions request failed due to " + status);
      }
    }
  );
    @endif
}        
            function setMarkers(map) {
                var image = {
                  @if($captain)
                    @if($captain->available == 'false')
                      url: "{{url('img/offline.png')}}",
                    @elseif($captain->available == 'true' && $captain->have_order == 'false')
                      url: "{{url('img/available.png')}}",
                    @else
                        @if($order)
                           @if( ($order->captain_in_road == 'true' || $order->captain_arrived == 'true') && ($order->start_journey == 'false') )
                            url: "{{url('img/tocustomer.png')}}",
                           @else
                            url: "{{url('img/onjob.png')}}",
                           @endif
                        @endif   
                    @endif
                  @else  
                      url: "{{url('img/offline.png')}}",
                  @endif
                    // size: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0)
                };
                var shape = {
                    coords: [1, 1, 1, 20, 18, 20, 18, 1],
                    type: 'poly'
                };
                
       <?php $i = 0;?>
        var places = [
        @if($captain)
            ["الاسم: <?=$captain->name?> <br/> الهاتف: <?=$captain->phonekey.$captain->phone?> <br/> كود القائد: <?=$captain->pin_code?> <br/> <?=($order)? 'رقم الرحلة '.$order->id :''; ?>", <?=$captain->lat;?>, <?=$captain->long?>, <?=$i;?>] ,
        @else
          @if(isset($allCaptains))
            @foreach($allCaptains as $cptn)
              ["الاسم: <?=$cptn->name?> <br/> الهاتف: <?=$cptn->phonekey.$cptn->phone?> <br/> كود القائد: <?=$cptn->pin_code?> <br/> <?=($order)? 'رقم الرحلة '.$order->id :''; ?>", <?=$cptn->lat;?>, <?=$cptn->long?>, <?=$i;?>] ,
              <?php $i++;?>
            @endforeach
          @endif
        @endif
        ];

              @if($captain)
                var i = 0;
                // for (var i = 0; i < places.length; i++) {
                    var place = places[i];
                    var marker = new google.maps.Marker({
                        position: { lat: place[1], lng: place[2] },
                        // url : place[4],
                        map: map,
                        draggable: true,
                        icon: image,
                        shape: shape,
                        title: place[0],
                        zIndex: place[3]
                    });
                    markers.push(marker);
                    var infowindow = new google.maps.InfoWindow();
                    var content = place[0];
                    // google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                            // return function() {
                               infowindow.setContent(content);
                               // infowindow.setOptions({minWidth: 200});
                               infowindow.open(map,marker);
                            // };
                        // })(marker,content,infowindow)); 

                // }
              @else
                var i = 0;
                for (var i = 0; i < places.length; i++) {
                    var place = places[i];
                    var marker = new google.maps.Marker({
                        position: { lat: place[1], lng: place[2] },
                        // url : place[4],
                        map: map,
                        draggable: true,
                        icon: image,
                        shape: shape,
                        title: place[0],
                        zIndex: place[3]
                    });
                    markers.push(marker);
                    var infowindow = new google.maps.InfoWindow();
                    var content = place[0];
                    google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                            return function() {
                               infowindow.setContent(content);
                               // infowindow.setOptions({minWidth: 200});
                               infowindow.open(map,marker);
                            };
                        })(marker,content,infowindow)); 

                }
              @endif

                @if($captain)
                  setInterval(function(){
                        var captain_id = <?=$captain->id;?>;
                        setMapOnAll(null);
                        markers = [];
                        $.get("<?=url('admin/getCurrentCaptainLocation');?>"+'/'+captain_id,'',function(result){

                              if(result.data.type == 'captain'){
                                if(result.data.captain.available == 'false'){
                                  var url =  "{{url('img/offline.png')}}";
                                }else if(result.data.captain.available == 'true' && result.data.captain.have_order == 'false'){
                                  var url = "{{url('img/available.png')}}";
                                }else{
                                    if(result.data.order != false){
                                       if( (result.data.order.captain_in_road == 'true' || result.data.order.captain_arrived == 'true' ) && (result.data.order.start_journey == 'false') ){
                                        var url = "{{url('img/tocustomer.png')}}";
                                       }else{
                                        var url = "{{url('img/onjob.png')}}";
                                       }
                                    }   
                                }
                              }
                              $('#num_tocustomer').text('الي العميل ('+result.data.num_tocustomer+')');
                              $('#num_onjob').text('فى رحلة ('+result.data.num_onjob+')');
                              $('#num_offline').text('غير متاح ('+result.data.num_offline+')');
                              $('#num_available').text('متاح ('+result.data.num_available+')');

                            var image = {
                              url : url,
                              origin: new google.maps.Point(0, 0),
                              anchor: new google.maps.Point(0, 0)
                            };
                            var shape = {
                                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                                type: 'poly'
                            };
                            var i = 0;
                                var place = places[i];
                                var marker = new google.maps.Marker({
                                    position: { lat: result.data.captain.lat, lng: result.data.captain.long },
                                    map: map,
                                    draggable: true,
                                    icon: image,
                                    shape: shape,
                                    title: place[0],
                                    zIndex: place[3]
                                });
                                markers.push(marker);
                                var infowindow = new google.maps.InfoWindow();
                                var content = place[0];
                                // google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                                    // return function() {
                                       infowindow.setContent(content);
                                       // infowindow.setOptions({minWidth: 200});
                                       infowindow.open(map,marker);
                                    // };
                                // })(marker,content,infowindow)); 

                        });

                  }, 15000);
                @else
                    setInterval(function(){
                          setMapOnAll(null);
                          markers = [];
                          $.get("<?=url('admin/getOfflineCaptainsLocation');?>",'',function(results){
                             
                              $('#num_tocustomer').text('الي العميل ('+results.data.num_tocustomer+')');
                              $('#num_onjob').text('فى رحلة ('+results.data.num_onjob+')');
                              $('#num_offline').text('غير متاح ('+results.data.num_offline+')');
                              $('#num_available').text('متاح ('+results.data.num_available+')');

                            var image = {
                                  url: "{{url('img/offline.png')}}",
                                // size: new google.maps.Size(30, 30),
                                origin: new google.maps.Point(0, 0),
                                anchor: new google.maps.Point(0, 0)
                            };
                            var shape = {
                                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                                type: 'poly'
                            };
                            var i = 0;
                            var places = [];
                              results.data.captains.forEach(function(captain){
                                places.push(["الاسم: "+captain.name+" <br/> الهاتف:"+captain.phonekey+""+captain.phone+" <br/> كود القائد: "+captain.pin_code, captain.lat, captain.long,i]);
                                i++;
                              });
                            // console.log(places);
                            var i = 0;
                            for (var i = 0; i < places.length; i++) {
                                var place = places[i];
                                var marker = new google.maps.Marker({
                                    position: { lat: place[1], lng: place[2] },
                                    // url : place[4],
                                    map: map,
                                    draggable: true,
                                    icon: image,
                                    shape: shape,
                                    title: place[0],
                                    zIndex: place[3]
                                });
                                markers.push(marker);
                                
                                var infowindow = new google.maps.InfoWindow();
                                var content = place[0];
                                google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                                        return function() {
                                           infowindow.setContent(content);
                                           // infowindow.setOptions({minWidth: 200});
                                           infowindow.open(map,marker);
                                        };
                                    })(marker,content,infowindow)); 

                            }
                          });

                    }, 15000);                
                @endif
            }

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

//end map
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=setting('google_places_key');?>&callback=initMap&language=ar"></script>
@endsection