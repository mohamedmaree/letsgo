@extends('dashboard.layout.master')
    @section('title')
    مركز الكول سنتر
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
        .fa-circle{
            color: green;
        }
        .offline{color: crimson
        }
        .leaderItem{
            display: flex;
            align-items: center;
            padding: 6px 0px;

        }

        .leaderItem:not(:last-child){
            border-bottom: 1px solid #eee;
        }
        .leaderItem > span{
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .leaderItem > span > a{
            flex: 1;
        }
        .share{margin-left: 4px}
        .travel > span{
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
        }
        .travel > span > a,
        .travel > span > span{
            flex-basis: 50%;
        }
        .leader > span{
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            padding-bottom: 8px;
        }
        .leader > span > a{width:50%}
        .leader > span > span{width:50%}
        .loading-page{
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 99;
            top: 0;
            right: 0;
            background-color: #333;
            display: none;
        }
        #loader{
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 10px solid #dcefff;
            border-top: 10px solid blue;
            animation: rotate 2s infinite ease;
        }
        @keyframes rotate{
            0%{
                transform: none;
            }
            100%{
                transform: rotate(1turn);
            }
        }
        #ctn{
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>

<div class="panel panel-flat first">
    <div class="panel-heading">
            <div class="row">
               <div class="col-lg-12">
                        <div class="card visitors-card">
                            <div class="card-content">
                              <h2 style="margin-right: 38%;">مركز الكول سنتر</h2>
                                <div class="map" id="map" style="with:90%;height:400px;"> 
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-lg-12">
                    <div style="background-color: #333;padding: 15px ;">
                        <div class="card visitors-card">
                            <div class="card-content">
                                <div style="display: grid;">
                                    <form action="{{url('admin/searchCaptainsAndOrders')}}" method="POST" >
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
                                        </div>
                                        <div>
                                            <input type="text" name="search" value="{{($search)??''}}" style="background: #111;border: none;border-radius: 0;color:#fff" class="form-control" required>
                                        </div>
                                        <button style="display: block;margin: 15px auto 0" type="submit" class="btn btn-primary">بحث</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>   
    </div>  
</div>
<div class="loading-page">
    <div id="ctn">
        <div id="loader"></div>
    </div>
    </div>
<div class="last-trips-info"> 
    @if($order ||  $captain)
    <div class="panel panel-flat first">
        <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-6">
                            <div class="card visitors-card">
                                <div class="card-content">
                                    <div style="display: grid;" class="leader">
                                        <span style="font-size: 15px; font-weight: bold">بيانات القائد</span>
                                        @if($captain)
                                            <span>الاسم : <a href="{{url('admin/userProfile/'.$captain->id)}}">{{$captain->name}} </a></span>
                                            <span>كود القائد : <a href="{{url('admin/userProfile/'.$captain->id)}}">{{$captain->pin_code}} </a></span>
                                            <span>رقم الهاتف : <a href="{{url('admin/userProfile/'.$captain->id)}}">{{'0'.$captain->phone}} </a></span>
                                            <span>الجنس : <span>{{($captain->gender == 'female')?'انثي':'ذكر'}} </span></span>
                                            <span>عدد الرحلات المكتملة : <span>{{$captain->num_done_orders}} </span></span>
                                            <span>السيارة :
                                                <span>
                                                     @if($captainCar = $captain->currentCar)
                                                        {{($captainCar->type->name_ar)??''}} -

                                                        النوع : ( {{$captainCar->brand}} - {{$captainCar->model}} - {{$captainCar->year}} )
                                                        رقم اللوحة : {{$captainCar->car_number}}
                                                    @endif
                                                </span>
                                            </span>  
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div> 
                    <div class="col-lg-6">
                            <div class="card visitors-card">
                                <div class="card-content">
                                    <div class="travel" style="display: grid;">
                                        <span style="font-size: 15px; font-weight: bold">بيانات الرحلة </span>
                                        @if($order)
                                            <span>رقم الرحلة : <a href="{{url('admin/showOrder/'.$order->id)}}">{{$order->id}} </a></span>
                                            <span>العميل : <a href="{{url('admin/userProfile/'.$order->user_id)}}">{{$order->user->name}} </a></span>
                                            <span>رقم الهاتف : <a href="{{url('admin/userProfile/'.$order->user_id)}}">{{'0'.$order->user->phone}} </a></span>
                                            <span>تصنيف السيارة المطلوب : <span>{{($order->cartype->name_ar)??''}}</span></span>
                                            <span>جنس العميل : <span>{{($order->user->gender == 'female')?'انثي' : 'ذكر'}}</span> </span>
                                            <span>نقطة البداية : <a href="{{url('admin/showOrder/'.$order->id)}}">{{$order->start_address}} </a></span>
                                            <span>نقطة الوصول : <a href="{{url('admin/showOrder/'.$order->id)}}">{{$order->end_address}} </a></span>
                                            <span>السعر المتوقع : <span>{{$order->expected_price}}</span> </span>
                                            <span>القائد استلم الرحلة : <span>{{($order->reception_time)?'نعم'.' ('.$order->reception_time.')':'لا'}}</span> </span>
                                            <span>القائد فى الطريق : <span>{{($order->captain_in_road == 'true')?'نعم':'لا'}}</span> </span>
                                            <span>القائد وصل لاصطحاب الراكب : <span>{{($order->captain_arrived == 'true')?'نعم'.' ('.$order->captain_arrived_time.')':'لا'}}</span> </span>
                                            <span>القائد بدأ الرحلة : <span>{{($order->start_journey == 'true')?'نعم'.' ('.$order->start_journey_time.')':'لا'}}</span> </span>
                                            <span>الحالة :<span>{{$order->status}}</span></span>
                                            <span>ملاحظات :<span>{{$order->notes}}</span></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div> 
                </div>    
        </div>  
    </div>  
    <div class="panel panel-flat first" style="position: relative">
        <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-5">
                            <div class="card visitors-card">
                                <div class="card-content">
                                    <div style="display: grid;" >
                                        <span  style="font-size: 15px; font-weight: bold">القادة المقترحين اولاً (حيث نوع السيارة )</span>
                                        @if(isset($suggested_captains1))
                                        <div class="leaderItem1">
                                            <?php $arr_ids = [];?>
                                            @foreach($suggested_captains1 as $suggested_captain)
                                                <span class="leaderItem itemo" data-order="{{directDistance($suggested_captain->lat,$suggested_captain->long,$order->start_lat,$order->start_long)}}">
                                                    <span>
                                                        {!!  ($suggested_captain->available)?"<i class='fa fa-circle'aria-hidden='true'></i>":'<i class="fa fa-circle offline"aria-hidden="true"></i>'!!}
                                                        <a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{$suggested_captain->name}}</a>
                                                    |<a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{'0'.$suggested_captain->phone}}</a>
                                                    | الرحلات : <a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{$suggested_captain->num_done_orders}}</a>
                                                    | المسافة : <a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{directDistance($suggested_captain->lat,$suggested_captain->long,$order->start_lat,$order->start_long)}} km</a>
                                                    </span>
                                                    @if($order->status == 'open')
                                                    <button onclick="notifyCaptain('{{$order->id}}','{{$suggested_captain->id}}');this.disabled = true;" class="btn btn-primary share">اشعار</button>
                                                    @endif
                                                    <button onclick="clickableCaptain('{{$order->id}}','{{$suggested_captain->id}}');" class="btn btn-primary">ارفاق</button>
                                                </span>
                                                <?php $arr_ids[] = $suggested_captain->id;?>
                                            @endforeach
                                        </div>
                                        @endif

                                    </div>
                                    <div style="display: grid;">
                                        <span style="font-size: 15px; font-weight: bold">القادة المقترحين ثانيا (اختلاف نوع السيارة )</span>
                                        @if(isset($suggested_captains2))
                                        <div class="leaderItem2">
                                           @foreach($suggested_captains2 as $suggested_captain)
                                               @if(!in_array($suggested_captain->id,$arr_ids))
                                                                <span class="leaderItem itemo" data-order="{{directDistance($suggested_captain->lat,$suggested_captain->long,$order->start_lat,$order->start_long)}}">
                                                                    <span>
                                                                        {!!  ($suggested_captain->available)?"<i class='fa fa-circle'aria-hidden='true'></i>":'<i class="fa fa-circle offline"aria-hidden="true"></i>'!!}
                                                                        <a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{$suggested_captain->name}}</a>
                                                                    |<a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{'0'.$suggested_captain->phone}}</a>
                                                                    | الرحلات : <a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{$suggested_captain->num_done_orders}}</a>
                                                                    | المسافة : <a href="{{url('admin/userProfile/'.$suggested_captain->id)}}">{{directDistance($suggested_captain->lat,$suggested_captain->long,$order->start_lat,$order->start_long)}} km</a>
                                                                    </span>
                                                                @if($order->status == 'open')
                                                                    <button onclick="notifyCaptain('{{$order->id}}','{{$suggested_captain->id}}');this.disabled = true;"  class="btn btn-primary share">اشعار</button>
                                                                @endif    
                                                                    <button onclick="clickableCaptain('{{$order->id}}','{{$suggested_captain->id}}');" class="btn btn-primary">ارفاق</button>
                                                                </span>
                                               @endif
                                           @endforeach
                                       </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                    </div> 

                    <div class="col-lg-5">
                            <div class="card visitors-card">
                                <div class="card-content">
                                    <div style="display: grid;">
                                        <span style="font-size: 15px; font-weight: bold">القادة وصلهم اشعار بالرحلة </span>
                                        @if(isset($received_notify_captains))
                                            <div class="leaderItem3">
                                            @foreach($received_notify_captains as $received_notify_captain)
                                            <span class="leaderItem itemo"  data-order="{{directDistance($received_notify_captain->lat,$received_notify_captain->long,$order->start_lat,$order->start_long)}}">
                                                <span>
                                                    {!!  ($received_notify_captain->available)?"<i class='fa fa-circle'aria-hidden='true'></i>":'<i class="fa fa-circle offline"aria-hidden="true"></i>'!!}
                                                    <a href="{{url('admin/userProfile/'.$received_notify_captain->id)}}">{{$received_notify_captain->name}}</a>
                                                |<a href="{{url('admin/userProfile/'.$received_notify_captain->id)}}">{{'0'.$received_notify_captain->phone}}</a>
                                                | الرحلات : <a href="{{url('admin/userProfile/'.$received_notify_captain->id)}}">{{$received_notify_captain->num_done_orders}}</a>
                                                | المسافة : <a href="{{url('admin/userProfile/'.$received_notify_captain->id)}}">{{directDistance($received_notify_captain->lat,$received_notify_captain->long,$order->start_lat,$order->start_long)}} km</a>
                                                </span>
                                                @if($order->status == 'open')
                                                <button onclick="notifyCaptain('{{$order->id}}','{{$received_notify_captain->id}}');this.disabled = true;"  class="btn btn-primary share">اشعار</button>
                                                @endif
                                                <button onclick="clickableCaptain('{{$order->id}}','{{$received_notify_captain->id}}');" class="btn btn-primary">ارفاق</button>
                                            </span>
                                            @endforeach
                                           </div>
                                       @endif


                                    </div>
                                </div>
                            </div>
                    </div> 

                    <div class="col-lg-2">
                            <div class="card visitors-card">
                                <div class="card-content">
                                    <div style="display: grid;">
                                        <span>محادثات الرحلة </span>
                                        @if(isset($trip_conversations))
                                        @foreach($trip_conversations as $conversation)
                                        <span>-المحادثة مع   <a href="{{url('admin/userProfile/'.$conversation->user2)}}">{{($conversation->seconduser->name)??''}} </a> <a href="{{url('admin/chat/'.$conversation->id)}}"> - التفاصيل</a></span>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div> 

                </div>
        </div>  
    </div>  
    @endif

    <!-- persons -->
    <div class="panel panel-flat" style="background-color: rgb(243, 252, 236);">
      <div class="panel-heading">
        <h5 class="panel-title">رحلات توصيل الأشخاص المفتوحة</h5>
        <div class="heading-elements">
          <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="reload"></a></li>
                <!-- <li><a data-action="close"></a></li> -->
              </ul>
          </div>
      </div>
      <table class="table datatable-basic">
        <thead style="background-color: rgb(141, 190, 98);">
          <tr>
            <th>رقم الرحلة</th>
            <th>العميل</th>
            <th>نوع السيارة</th>
            <th>من </th>
            <th>الي </th>
            <th>تاريخ الاضافة</th>
            <th>التحكم</th>
          </tr>
        </thead>
        <tbody>
          @foreach($personsorders as $openOrder)
            <tr>
              <td>{{$openOrder->id}}</td>
              <td><a href="{{url('admin/userProfile/'.$openOrder->user_id)}}" target="_blank">{{($openOrder->user)?$openOrder->user->name:''}}</a></td>
              <td>{{($openOrder->cartype)?$openOrder->cartype->name_ar:''}}</td>
              <td>{{$openOrder->start_address }}</td>
              <td>{{$openOrder->end_address }}</td>
              <td>{{date('Y-m-d H:i',strtotime($openOrder->created_at))}}</td>
              <td>
                <form action="{{url('admin/searchCaptainsAndOrders')}}" method="POST">
                  @csrf
                  <input type="hidden" name="searchby" value="order_id">
                  <input type="hidden" name="show" value="order">
                  <input type="hidden" name="search" value="{{$openOrder->id}}">
                  <button type="submit" class="btn btn-default"> عرض علي الخريطة</button>
                </form>
                |            
                <a href="{{url('admin/showOrder/'.$openOrder->id)}}">التفاصيل</a>
              </td>       
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="panel panel-flat" style="background-color: rgb(243, 252, 236);">
      <div class="panel-heading">
        <h5 class="panel-title">رحلات توصيل الأشخاص قيد التنفيذ</h5>
        <div class="heading-elements">
          <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="reload"></a></li>
                <!-- <li><a data-action="close"></a></li> -->
              </ul>
          </div>
      </div>
      <table class="table datatable-basic">
        <thead style="background-color: rgb(141, 190, 98);">
          <tr>
            <th>رقم الرحلة</th>
            <th>العميل</th>
            <th>القائد</th>
            <th>نوع السيارة</th>
            <th>من </th>
            <th>الي </th>
            <th>تاريخ الاستلام</th>
            <th>تاريخ الاضافة</th>
            <th>التحكم</th>
          </tr>
        </thead>
        <tbody>
          @foreach($personsorders_inprogress as $inprogressOrder)
            <tr>
              <td>{{$inprogressOrder->id}}</td>
              <td><a href="{{url('admin/userProfile/'.$inprogressOrder->user_id)}}" target="_blank">{{($inprogressOrder->user)?$inprogressOrder->user->name:''}}</a></td>
              <td><a href="{{url('admin/userProfile/'.$inprogressOrder->captain_id)}}" target="_blank">{{($inprogressOrder->captain)?$inprogressOrder->captain->name:''}}</a></td>
              <td>{{($inprogressOrder->cartype)?$inprogressOrder->cartype->name_ar:''}}</td>
              <td>{{$inprogressOrder->start_address }}</td>
              <td>{{$inprogressOrder->end_address }}</td>
              <td>{{date('Y-m-d H:i',strtotime($inprogressOrder->reception_time))}}</td>
              <td>{{date('Y-m-d H:i',strtotime($inprogressOrder->created_at))}}</td>
              <td>
                <form action="{{url('admin/searchCaptainsAndOrders')}}" method="POST">
                  @csrf
                  <input type="hidden" name="searchby" value="order_id">
                  <input type="hidden" name="show" value="order">
                  <input type="hidden" name="search" value="{{$inprogressOrder->id}}">
                  <button type="submit" class="btn btn-default"> عرض علي الخريطة</button>
                </form>
                |            
                <a href="{{url('admin/showOrder/'.$inprogressOrder->id)}}">التفاصيل</a>
              </td>       
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="panel panel-flat" style="background-color: rgb(243, 252, 236);">
      <div class="panel-heading">
        <h5 class="panel-title">رحلات توصيل الأشخاص المغلقة في خلال 15 د</h5>
        <div class="heading-elements">
          <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="reload"></a></li>
                <!-- <li><a data-action="close"></a></li> -->
              </ul>
          </div>
      </div>
      <table class="table datatable-basic">
        <thead style="background-color: rgb(141, 190, 98);">
          <tr>
            <th>رقم الرحلة</th>
            <th>العميل</th>
            <th>نوع السيارة</th>
            <th>من </th>
            <th>الي </th>
            <th>سبب الاغلاق </th>
            <th>تاريخ الاضافة</th>
            <th>التحكم</th>
          </tr>
        </thead>
        <tbody>
          @foreach($personsorders_closed as $closedOrder)
            <tr>
              <td>{{$closedOrder->id}}</td>
              <td><a href="{{url('admin/userProfile/'.$closedOrder->user_id)}}" target="_blank">{{($closedOrder->user)?$closedOrder->user->name:''}}</a></td>
              <td>{{($closedOrder->cartype)?$closedOrder->cartype->name_ar:''}}</td>
              <td>{{$closedOrder->start_address }}</td>
              <td>{{$closedOrder->end_address }}</td>
              <td>{{$closedOrder->close_reason }}</td>
              <td>{{date('Y-m-d H:i',strtotime($closedOrder->created_at))}}</td>
              <td>
                <form action="{{url('admin/searchCaptainsAndOrders')}}" method="POST">
                  @csrf
                  <input type="hidden" name="searchby" value="order_id">
                  <input type="hidden" name="show" value="order">
                  <input type="hidden" name="search" value="{{$closedOrder->id}}">
                  <button type="submit" class="btn btn-default"> عرض علي الخريطة</button>
                </form>
                |            
                <a href="{{url('admin/showOrder/'.$closedOrder->id)}}">التفاصيل</a>
              </td>       
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

</div>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
<script type="text/javascript">
//start map
        let map;
        let markers = [];

            function initMap() {
                    const directionsService = new google.maps.DirectionsService();
                    const directionsRenderer = new google.maps.DirectionsRenderer({preserveViewport: true});
                  @if($captain)
                    maplat = <?=$captain->lat;?>;
                    maplng = <?=$captain->long;?>;
                  @else
                    maplat = 24.774265;
                    maplng = 46.738586;
                  @endif
                   

                    var myLatLng = { lat: maplat, lng: maplng };
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 10,
                        center: myLatLng ,
                        // disableDefaultUI: false,
                        mapTypeId: google.maps.MapTypeId.TERRAIN,
                    });
                    setMarkers(map);
                    @if($order)
                      directionsRenderer.setMap(map);
                      calculateAndDisplayRoute(directionsService, directionsRenderer);
                    @endif
            }
        
function calculateAndDisplayRoute(directionsService, directionsRenderer) {

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
        console.log("Directions request failed due to " + status);
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
                      url: "{{url('img/onjob.png')}}",
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

                        //   lineCoordinates.push({ lat: parseFloat(data.lat), lng: parseFloat(data.lng) });
                        // console.log(lineCoordinates);
                        //   const flightPath = new google.maps.Polyline({
                        //     path: lineCoordinates,
                        //     geodesic: true,
                        //     strokeColor: "#FF0000",
                        //     strokeOpacity: 1.0,
                        //     strokeWeight: 2,
                        //   });
                        //   flightPath.setMap(map);


                }
            @endif

        });
            }

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (let i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

//end map
</script>
    <script>
        // $.ajaxSetup({
        //  headers : { "X-CSRF-TOKEN" :jQuery(`meta[name="csrf-token"]`). attr("content")}
        // });
        function clickableCaptain(order_id, captain_id) {
            var result = confirm('انت علي وشك إرفاق الطلب الي سائق ، هل انت متأكد ؟ ');
            if (result == false) {
                e.preventDefault();
            }
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
                        // location.reload();
                        location.href = "{{url('admin/callCenter')}}";
                    }

                }
            });
        }

        function notifyCaptain(order_id, captain_id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('notifyOrderToCaptain') }}',
                data: {
                    _token: "{{csrf_token()}}",
                    order_id: order_id,
                    captain_id: captain_id,
                },
                success: function (data) {
                    if (data.key == 'success') {
                    }
                }
            });
        }

        setInterval(function(){
            $(".loading-page").fadeIn("slow");
           $.ajax({
                type: 'POST',
                url: '{{ route("searchCaptainsAndOrdersAjax") }}',
                data: {
                    _token: "{{csrf_token()}}",
                    searchby: '{{($searchby)??''}}',
                    search: '{{($search)??''}}',
                },

               success: function (data) {
                   $(".loading-page").fadeOut("slow");
                 // $('.last-trips-info').html('');
                 $('.last-trips-info').html(data);

                }
            });

        }, 60000);

    </script>

    <script>
        function sortLiElements1(a, b) {
            return parseFloat($(a).data('order')) - parseFloat($(b).data('order'));
        }
        $('.leaderItem1').html($('.leaderItem1 .itemo').sort(sortLiElements1));

        function sortLiElements2(a, b) {
            return parseFloat($(a).data('order')) - parseFloat($(b).data('order'));
        }
        $('.leaderItem2').html($('.leaderItem2 .itemo').sort(sortLiElements2));

        function sortLiElements3(a, b) {
            return parseFloat($(a).data('order')) - parseFloat($(b).data('order'));
        }
        $('.leaderItem3').html($('.leaderItem3 .itemo').sort(sortLiElements3));


    </script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=setting('google_places_key');?>&callback=initMap&language=ar"></script>
@endsection