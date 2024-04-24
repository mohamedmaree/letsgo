@extends('dashboard.layout.master')
@section('title')
    عرض رحلة 
        {{($order->user)?$order->user->name:''}}
    
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">عرض رحلة :
                    {{($order->user)?$order->user->name:''}}
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel panel-flat">
            <div class="panel-body">
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-success">
                        @if($order->order_type == 'order')
                            <div class="col-sm-4">العميل : <a href="{{url('admin/userProfile/'.$order->user_id)}}">{{($order->user)?$order->user->name:''}} </a></div>
                        @endif
                        <div class="col-sm-4">القائد : <a href="{{url('admin/userProfile/'.$order->captain_id)}}"> {{($order->captain)?$order->captain->name:''}} </a></div>
                        <div class="col-sm-4">حالة الرحلة :
                            @if($order->status == 'open')
                                مفتوح
                            @elseif($order->status == 'inprogress')
                                قيد التنفيذ
                            @elseif($order->status == 'finished')
                                منتهي
                            @else
                                مغلق
                            @endif
                        </div>
                    </div>

                    <br>
                    <table class="table table-bordered table-strapped">
                        <tbody>
                        <tr>
                            <td> اسم العميل </td>
                            <td><a href="{{url('admin/userProfile/'.$order->user_id)}}">{{($order->user->name)??''}}</a></td>
                        </tr>
                         <tr>
                            <td> هاتف العميل </td>
                            <td><a href="{{url('admin/userProfile/'.$order->user_id)}}">0{{($order->user->phone)??''}}</a></td>
                        </tr>
                        <tr>
                            <td> البريد الالكتروني للعميل </td>
                            <td><a href="{{url('admin/userProfile/'.$order->user_id)}}">{{($order->user->email)??''}}</a></td>
                        </tr>
                         <tr>
                            <td> اسم القائد </td>
                            <td><a href="{{url('admin/userProfile/'.$order->captain_id)}}">{{($order->captain->name)??''}}</a></td>
                        </tr>
                         <tr>
                            <td> هاتف القائد </td>
                            <td><a href="{{url('admin/userProfile/'.$order->captain_id)}}">0{{($order->captain->phone)??''}}</a></td>
                        </tr>
                        <tr>
                            <td> كود القائد </td>
                            <td><a href="{{url('admin/userProfile/'.$order->captain_id)}}">{{($order->captain->pin_code)??''}}</a></td>
                        </tr>
                        <tr>
                            <td> نوع السيارة</td>
                            <td>{{($order->cartype)?$order->cartype->name_ar: ''}}</td>
                        </tr>
                        <tr>
                            <td> معلومات السيارة</td>
                            <td>{{($order->car)?$order->car->brand.'('.$order->car->model.'-'.$order->car->year.')' : ''}}</td>
                        </tr>
                        <tr>
                            <td> رقم السيارة</td>
                            <td>{{($order->car)?$order->car->car_number:''}}</td>
                        </tr>
                        <tr>
                            <td> صورة السيارة</td>
                            <td>
                                <img src="{{($order->car)?url('img/car/'.$order->car->image):url('img/car/default.png')}}">
                            </td>
                        </tr>
                        <tr>
                            <td> عدد الركاب الحاليين للرحلة</td>
                            <td>{{$order->current_order_persons}}</td>
                        </tr>
                        <tr>
                            <td> العدد الأقصي للركاب</td>
                            <td>{{$order->max_car_persons}}</td>
                        </tr>
                        <tr>
                            <td> الحمولة الأقصي للرحلة</td>
                            <td>{{$order->max_car_weight}}</td>
                        </tr>
                        <tr>
                            <td> وقت الرحلة</td>
                            <td>{{($order->type=='now')?'الأن':'لاحقة ('.$order->later_order_date.' '.$order->later_order_time.')'}}</td>
                        </tr>
                        <tr>
                            <td> طريقة حساب أرخص</td>
                            <td> @if($order->cheaper_way=='bids')
                                    تقديم عروض علي الرحلة
                                @elseif($order->cheaper_way=='share')
                                    السماح بمشاركة راكب
                                @else
                                    لا يوجد
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td> نقطة الانطلاق</td>
                            <td>{{$order->start_address}}</td>
                        </tr>
                        <tr>
                            <td> نقطة النهاية</td>
                            <td>{{$order->end_address}}</td>
                        </tr>
                        <tr>
                            <td> السعر المتوقع</td>
                            <td>{{$order->expected_price}} {{$order->currency_ar}}</td>
                        </tr>
                        <tr>
                            <td> سعر الرحلة</td>
                            <td>{{$order->price}} {{$order->currency_ar}}</td>
                        </tr>
                        
                        <tr>
                            <td> ضريبة القيمة المضافة</td>
                            <td>{{$order->vat}} {{$order->currency_ar}}</td>
                        </tr>
                        <tr>
                            <td> عمولة وصل</td>
                            <td>{{$order->wasl}} {{$order->currency_ar}}</td>
                        </tr>
                        <tr>
                            <td> كوبونات الخصم</td>
                            <td>{{$order->coupon_discount}} {{$order->currency_ar}}</td>
                        </tr>
                        <tr>
                            <td> المبلغ المطلوب نقدا</td>
                            <td>{{$order->required_price}} {{$order->currency_ar}}</td>
                        </tr>

                        <tr>
                            <td> تأكيد الدفع</td>
                            <td>{{($order->confirm_payment == 'true')?'تم':'لم يتم'}}</td>
                        </tr>
                        <tr>
                            <td> اجمالي المدفوع نقداً</td>
                            <td>{{$order->total_payments}} {{$order->currency_ar}}</td>
                        </tr>
                        <tr>
                           <td> المدفوع من المحفظة </td>
                           <td>{{$order->paid_balance}} {{$order->currency_ar}}</td>
                        </tr>  
                        <tr>
                           <td> المبلغ الزيادة المضاف لمحفظة العميل </td>
                           <td>{{$order->added_balance}} {{$order->currency_ar}}</td>
                        </tr>  
                        <tr>
                            <td> الدولة</td>
                            <td>{{($order->country)?$order->country->name_ar:''}}
                                ({{($order->city)?$order->city->name_ar:''}})
                            </td>
                        </tr>
                        <tr>
                            <td> نوع الدفع</td>
                            <td>{{($order->payment_type=='cash')?'نقدي':'بطاقة ائتمانية'}}</td>
                        </tr>
                        <tr>
                            <td> صورة الشحنة</td>
                            <td><img src="{{($order->shipment_image)?url('/img/order/'.$order->shipment_image):''}}"/>
                            </td>
                        </tr>
                        <tr>
                            <td> نوع الهوية</td>
                            <td>{{$order->identity_type}}</td>
                        </tr>
                        <tr>
                            <td> رقم تحقيق الشخصية</td>
                            <td>{{$order->identity_number}}</td>
                        </tr>
                        <tr>
                            <td> المسافة المتوقعة</td>
                            <td>{{$order->expected_distance}}</td>
                        </tr>
                        <tr>
                            <td> المدة المتوقعة</td>
                            <td>{{$order->expected_period}}</td>
                        </tr>
                        <tr>
                            <td> مسافة الرحلة</td>
                            <td>{{$order->distance}}</td>
                        </tr>
                        <tr>
                            <td> مدة الرحلة</td>
                            <td>{{$order->period}}</td>
                        </tr>
                        <tr>
                            <td> وقت الانتظار المبدئي</td>
                            <td>{{$order->initial_wait}} دقيقة</td>
                        </tr>
                        <tr>
                            <td> وقت الانتظار أثناء الرحلة</td>
                            <td>{{$order->during_order_wait}} دقيقة</td>
                        </tr>
                        <tr>
                            <td> تقييم العميل للرحلة</td>
                            <td>{{$order->customerRating}}</td>
                        <tr>
                            <td> وقت استقبال الرحلة</td>
                            <td>{{$order->reception_time}}</td>
                        </tr>
                        <tr>
                            <td> القائد فى الطريق</td>
                            <td>{{($order->captain_in_road == 'true')?'نعم':'لا'}}</td>
                        </tr>
                        <tr>
                            <td> القائد وصل لاصطحاب الراكب</td>
                            <td>{{($order->captain_arrived == 'true')?'نعم'.' ('.$order->captain_arrived_time.')':'لا'}}</td>
                        </tr>
                        <tr>
                            <td> القائد بدأ الرحلة</td>
                            <td>{{($order->start_journey == 'true')?'نعم'.' ('.$order->start_journey_time.')':'لا'}}</td>
                        </tr>
                        <tr>
                            <td> وقت انهاء الرحلة</td>
                            <td>{{$order->end_journey_time}}</td>
                        </tr>
                        <tr>
                            <td> ملاحظات الرحلة</td>
                            <td>{{$order->notes}}</td>
                        </tr>
                        @if($order->close_reason)
                            <tr>
                                <td>سبب الإغلاق</td>
                                <td>{{$order->close_reason}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td> ارسال الرحله لعلم</td>
                            <td>{{($order->sent_to_wasl == 'true')? 'تم الارسال': 'لم يتم الارسال بعد'}}</td>
                        </tr>
                        <tr>
                            <td> تاريخ الرحلة</td>
                            <td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="panel panel-flat first">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card visitors-card">
                                        <div class="card-content">
                                            <div class="map" id="map" style="with:90%;height:500px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-lg-12">
                                <input type="checkbox" name="types[]" value="all" class="types" checked> الكل
                                <input type="checkbox" name="types[]" value="available" class="types" checked> المتاحين
                                <input type="checkbox" name="types[]" value="offline" class="types" checked> الغير متاحين
                            </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="btn btn-warning col-sm-4" onclick="closeOrder();">إلغاء الرحلة <i
                                    style="color: #fff" class="glyphicon glyphicon-lock"></i></div>
                        <form action="{{route('AdmincloseOrder')}}" method="POST" id="closeOrder">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$order->id}}">
                        </form>
                        <div class="btn btn-info col-sm-4" onclick="finishOrder();">إنهاء الرحلة <i style="color: #fff"
                                                                                                    class="glyphicon glyphicon-saved"></i>
                        </div>
                        <form action="{{route('AdminfinishOrder')}}" method="POST" id="finishOrderform">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$order->id}}">
                        </form>
                        <div class="btn btn-danger col-sm-4" onclick="deleteOrder()">حذف <i style="color: #fff"
                                                                                            class="icon-trash"></i>
                        </div>
                        <form action="{{route('AdmindeleteOrder')}}" method="POST" id="deleteOrder">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$order->id}}">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- javascript -->
@endsection

@section('script')
    <script>

        function deleteOrder() {
            var x = confirm("هل أنت متأكد؟");
            if (x == false) {
                return false
            }
            $("#deleteOrder").submit();
        }

        function closeOrder() {
            var x = confirm("هل أنت متأكد؟");
            if (x == false) {
                return false
            }
            $("#closeOrder").submit();
        }

        function finishOrder() {
            var x = confirm("هل أنت متأكد؟");
            if (x == false) {
                return false
            }
            $("#finishOrderform").submit();
        }
    </script>


    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?=setting('google_places_key');?>&language=ar"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>

    <script type="text/javascript">
        //start map
        let map;
        let markers = [];
        var lineCoordinates = [];

        $(document).ready(function () {
            // setTimeout(function () {
            initMap();

            // }, 2000)
            function initMap() {

                maplat = <?=($order->current_lat)??24.774265;?>;
                maplng = <?=($order->current_long)??46.738586;?>;

                var shape = {
                    coords: [1, 1, 1, 20, 18, 20, 18, 1],
                    type: 'poly'
                };

                var myLatLng = {lat: maplat, lng: maplng};
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: myLatLng,
                    // disableDefaultUI: false,
                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                });

                <?php $i = 0;?>
                var places = [
                        @if($order)
                    [
                        "<br/> نقطة البداية: <?=$order->start_address?>
                            <br/> نقطة الوصول: <?=$order->end_address?>",
                        <?=$order->lat;?>, <?=$order->long?>, <?=$i;?>
                    ],
                    @endif
                ];

                var i = 0;
                // for (var i = 0; i < places.length; i++) {
                var place = places[i];
                var infowindow = new google.maps.InfoWindow();
                var content = place[0];


                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map,
                    shape: shape,
                });
                infowindow.setContent(content);
                infowindow.open(map, marker);
                setMarkers(map);
            }

            function setMarkers(map) {

                var shape = {
                    coords: [1, 1, 1, 20, 18, 20, 18, 1],
                    type: 'poly'
                };

                <?php $i = 0;?>
                var places = [
                        @if($captain)
                    ["الاسم: <?=$captain->name?> <br/> الهاتف: <?=$captain->phonekey . $captain->phone?> <br/> كود القائد: <?=$captain->pin_code?> <br/> <?=($order) ? 'رقم الرحلة ' . $order->id : ''; ?>", <?=$captain->lat;?>, <?=$captain->long?>, <?=$i;?>,
                {
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
                        url: "{{url('img/available.png')}}",
                    @endif
                    // size: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0)
                }
                    , <?=$captain->id?>
                    ],
                        @endif
                        @if(isset($allCaptains))
                        @foreach($allCaptains as $cptn)
                    ["الاسم: <?=$cptn->name?> <br/> الهاتف: <?=$cptn->phonekey . $cptn->phone?> <br/> كود القائد: <?=$cptn->pin_code?> <br/> <?=($order) ? 'رقم الرحلة ' . $order->id : ''; ?>", <?=$cptn->lat;?>, <?=$cptn->long?>, <?=$i;?>,
                {
                    @if($cptn)
                            @if($cptn->available == 'false')
                                url: "{{url('img/offline.png')}}",
                            @elseif($cptn->available == 'true' && $cptn->have_order == 'false')
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
                        url: "{{url('img/available.png')}}",
                    @endif
                    // size: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0)
                }
                , <?=$cptn->id?>
                    ],
                    <?php $i++;?>
                    @endforeach
                    @endif
                ];

                @if($captain)
                var i = 0;
                // for (var i = 0; i < places.length; i++) {
                var place = places[i];
                var marker = new google.maps.Marker({
                    id : place[5],
                    position: {lat: place[1], lng: place[2]},
                    // url : place[4],
                    map: map,
                    draggable: true,
                    icon: place[4],
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
                infowindow.open(map, marker);
                // };
                // })(marker,content,infowindow));

                // }
                @endif
                @if(isset($allCaptains))
                var i = 0;
                for (var i = 0; i < places.length; i++) {
                    var place = places[i];
                    var marker = new google.maps.Marker({
                        id : place[5],
                        position: {lat: place[1], lng: place[2]},
                        // url : place[4],
                        map: map,
                        draggable: true,
                        icon: place[4],
                        shape: shape,
                        title: place[0],
                        zIndex: place[3]
                    });
                    markers.push(marker);
                    var orderId = "{{$order->id}}";

                    google.maps.event.addListener(marker, 'click', (function (e) {
                        return function () {
                            var result = confirm('انت علي وشك إرفاق الطلب الي سائق ، هل انت متأكد ؟ '+this.id);
                            if (result == false) {
                                e.preventDefault();
                            }
                            var captainId = this.id;
                            return clickableCaptain(orderId, captainId)
                        }
                    })(marker, i));

                    var infowindow = new google.maps.InfoWindow();
                    var content = place[0];
                    // google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                    // return function () {
                    infowindow.setContent(content);
                    // infowindow.setOptions({minWidth: 200});
                    infowindow.open(map, marker);
                    // };
                    // })(marker, content, infowindow));

                }
                @endif

        
        var socket = io.connect('https://letsgo-app.net:4640');
            console.log(socket);
        socket.on('trackorder', function (data) {
            @if($captain)
            var data = JSON.parse(data);
            var current_captain_id = <?=$captain->id?>;
                if(data.captain_id == current_captain_id){
            //update map
                    setMapOnAll(null);
                    markers = [];
                   
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
                                url: "{{url('img/available.png')}}",
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

                        var i = 0;
                        var place = places[i];
                        var marker = new google.maps.Marker({
                            id : place[5],
                            position: {lat: parseFloat(data.lat), lng: parseFloat(data.lng)},
                            map: map,
                            draggable: false,
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
                        infowindow.open(map, marker);
                        // };
                        // })(marker,content,infowindow));

                          lineCoordinates.push({ lat: parseFloat(data.lat), lng: parseFloat(data.lng) });
                        console.log(lineCoordinates);
                          const flightPath = new google.maps.Polyline({
                            path: lineCoordinates,
                            geodesic: true,
                            strokeColor: "#FF0000",
                            strokeOpacity: 1.0,
                            strokeWeight: 2,
                          });
                          flightPath.setMap(map);


                }
            @endif

        });

                @if($allCaptains)
                setInterval(function () {
                    setMapOnAll(null);
                    
                    var types = [];
                    $.each($("input[name='types[]']:checked"), function(){
                        types.push($(this).val());
                    });

                    markers = [];
                    $.get("<?=url('admin/getNearformOrderAvailableCaptainsLocation/' . $order->id);?>"+"/"+ types, '', function (results) {

                        var image = {
                            url: "{{url('img/available.png')}}",
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
                        results.data.captains.forEach(function (captain) {
                    
                    if(captain){
                            if(captain.available == 'false'){
                               var image_url = "{{url('img/offline.png')}}";
                            }else if((captain.available == 'true') && (captain.have_order == 'false')){
                                var image_url = "{{url('img/available.png')}}";
                            }else if((captain.available == 'true') && (captain.have_order == 'true')){
                                var image_url = "{{url('img/onjob.png')}}";
                            }else{
                                var image_url = "{{url('img/offline.png')}}";
                            }
                    }else{
                        var image_url = "{{url('img/available.png')}}";
                    }

                        var image = {       
                            url: image_url,
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(0, 0)
                        }
                            places.push(["الاسم: " + captain.name + " <br/> الهاتف:" + captain.phonekey + "" + captain.phone + " <br/> كود القائد: " + captain.pin_code, captain.lat, captain.long, i, captain.id,image]);
                            i++;
                        });
                        var i = 0;
                        for (var i = 0; i < places.length; i++) {
                            var place = places[i];
                            var marker = new google.maps.Marker({
                                position: {lat: place[1], lng: place[2]},
                                // url : place[4],
                                map: map,
                                draggable: true,
                                icon: place[5],
                                shape: shape,
                                title: place[0],
                                zIndex: place[3],
                                id: place[4]
                            });
                            markers.push(marker);

                            var orderId = "{{$order->id}}";

                            google.maps.event.addListener(marker, 'click', (function (e) {
                                return function () {
                                    var result = confirm('انت علي وشك إرفاق الطلب الي سائق ، هل انت متأكد ؟ '+this.id);
                                    if (result == false) {
                                        e.preventDefault();
                                    }
                                    var captainId = this.id;
                                    return clickableCaptain(orderId, captainId)
                                }
                            })(marker, i));

                            var infowindow = new google.maps.InfoWindow();
                            var content = place[0];
                            // google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                            // return function () {
                            infowindow.setContent(content);
                            // infowindow.setOptions({minWidth: 200});
                            infowindow.open(map, marker);
                            // };
                            // })(marker, content, infowindow));

                        }
                    });

                }, 30000);

                @endif


$(".types").change(function() {
                    var types = [];
                    $.each($("input[name='types[]']:checked"), function(){
                        types.push($(this).val());
                    });
                    setMapOnAll(null);

                    markers = [];
                    $.get("<?=url('admin/getNearformOrderAvailableCaptainsLocation/' . $order->id);?>"+"/"+ types, '', function (results) {

                        var image = {
                            url: "{{url('img/available.png')}}",
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
                        results.data.captains.forEach(function (captain) {
                    
                    if(captain){
                            if(captain.available == 'false'){
                               var image_url = "{{url('img/offline.png')}}";
                            }else if((captain.available == 'true') && (captain.have_order == 'false')){
                                var image_url = "{{url('img/available.png')}}";
                            }else if((captain.available == 'true') && (captain.have_order == 'true')){
                                var image_url = "{{url('img/onjob.png')}}";
                            }else{
                                var image_url = "{{url('img/offline.png')}}";
                            }
                    }else{
                        var image_url = "{{url('img/available.png')}}";
                    }

                        var image = {       
                            url: image_url,
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(0, 0)
                        }
                            places.push(["الاسم: " + captain.name + " <br/> الهاتف:" + captain.phonekey + "" + captain.phone + " <br/> كود القائد: " + captain.pin_code, captain.lat, captain.long, i, captain.id,image]);
                            i++;
                        });
                        var i = 0;
                        for (var i = 0; i < places.length; i++) {
                            var place = places[i];
                            var marker = new google.maps.Marker({
                                position: {lat: place[1], lng: place[2]},
                                // url : place[4],
                                map: map,
                                draggable: true,
                                icon: place[5],
                                shape: shape,
                                title: place[0],
                                zIndex: place[3],
                                id: place[4]
                            });
                            markers.push(marker);

                            var orderId = "{{$order->id}}";

                            google.maps.event.addListener(marker, 'click', (function (e) {
                                return function () {
                                    var result = confirm('انت علي وشك إرفاق الطلب الي سائق ، هل انت متأكد ؟ '+this.id);
                                    if (result == false) {
                                        e.preventDefault();
                                    }
                                    var captainId = this.id;
                                    return clickableCaptain(orderId, captainId)
                                }
                            })(marker, i));

                            var infowindow = new google.maps.InfoWindow();
                            var content = place[0];
                            // google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                            // return function () {
                            infowindow.setContent(content);
                            // infowindow.setOptions({minWidth: 200});
                            infowindow.open(map, marker);
                            // };
                            // })(marker, content, infowindow));

                        }
                    });

});



            }//end setMarkers


            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (let i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

            //end map

        });


    </script>

    <script>
        // $.ajaxSetup({
        //  headers : { "X-CSRF-TOKEN" :jQuery(`meta[name="csrf-token"]`). attr("content")}
        // });
        function clickableCaptain(order_id, captain_id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('attachOrderToCaptain') }}',
                data: {
                    _token: "{{csrf_token()}}",
                    order_id: order_id,
                    captain_id: captain_id,
                },
                success: function (data) {
                    if (data.key == 'success') {
                        location.reload();
                    }

                }
            });
        }
    </script>

@endsection

