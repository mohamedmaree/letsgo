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