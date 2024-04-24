@extends('dashboard.layout.master')
    @section('title')
    الأحصائيات 
    @endsection
<link href="{{asset('dashboard/plugins/materialize/css/materialize.min.css')}}" rel="stylesheet" type="text/css">     
@section('content')
<script src = "{{asset('dashboard/js/highcharts.js')}}"></script> 
<div class="panel panel-flat first">
    <div class="panel-heading">
                   <div class="row">
                    <!-- <a href="{{url('admin/trackingCenter')}}" class="btn btn-primary">مركز عمليات التتبع</a> -->
                       <div class="col-lg-12">
                            <div class="card visitors-card">
                                <div class="card-content">
                                  <h2 style="margin-right: 38%;">الرحلات الجديدة فى انتظار قائد</h2>
                                    <div class="map" id="map" style="with:90%;height:500px;"> </div>
                                </div>
                            </div>
                        </div>
                       <div class="col-lg-5">
                            <div class="card visitors-card">
                                <div class="card-content">
                                      <div id="flotchart1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card visitors-card">
                                <div class="card-content">
                                      <div id="flotchart2"></div>
                                </div>
                            </div>
                        </div>
                       <div class="col-lg-12">
                            <div class="card visitors-card">
                                <div class="card-content">
                                      <div id="flotchart3"></div>
                                </div>
                            </div>
                        </div>
                       <div class="col-lg-12">
                            <div class="card visitors-card">
                                <div class="card-content">
                                      <div id="flotchart4"></div>
                                </div>
                            </div>
                        </div>                                           
                </div>   

    </div>  
 </div>  
<div class="panel panel-flat second">
    <div class="panel-heading">
        <div class="row">
            
            <div class="col-lg-4">
                <div class="panel" style="background:#1abc9c">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="icon-vcard"></i>
                        </div>
                    <h3 class="no-margin">{{$num_users}}</h3>
                    <h3>اجمالي المستخدمين</h3>
                    </div>  
                <span class="range-bar" style="width:70%"></span>                                     
                </div>
            </div> 
            <div class="col-lg-4">
                <div class="panel" style="background:#1abc9c">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-queen"></i>
                        </div>
                    <h3 class="no-margin">{{$num_supervisiors}}</h3>
                    <h3>ادارة التطبيق</h3>
                    </div>  
                <span class="range-bar" style="width:70%"></span>                                     
                </div>
            </div> 
            <div class="col-lg-4">
                <div class="panel" style="background:#1abc9c">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="icon-vcard"></i>
                        </div>
                    <h3 class="no-margin">{{$num_clients}}</h3>
                    <h3>عدد العملاء</h3>
                    </div>  
                <span class="range-bar" style="width:70%"></span>                                     
                </div>
            </div>             

            <div class="col-lg-4">
                <div class="panel" style="background:#1abc9c">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-knight"></i>
                        </div>
                    <h3 class="no-margin">{{$num_captains}}</h3>
                    <h3>عدد القادة</h3>
                    </div>  
                <span class="range-bar" style="width:70%"></span>                                     
                </div>
            </div>   
            <div class="col-lg-4">
                <div class="panel" style="background:#1abc9c">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="icon-lock"></i>
                        </div>
                    <h3 class="no-margin">{{$num_roles}}</h3>
                    <h3>الصلاحيات</h3>
                    </div>
                <span class="range-bar" style="width:60%"></span>                                     
                </div>
            </div> 
            <div class="col-lg-4">
                <div class="panel" style="background:#f09748;">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="fa fa-car"></i>
                        </div>
                    <h3 class="no-margin">{{$num_cartypes}}</h3>
                    <h3>أنواع السيارات</h3>
                    </div>
                <span class="range-bar" style="width:15%"></span>                                     
                </div>
            </div>  
            <div class="col-lg-4">
                <div class="panel" style="background:#f09748;">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="fa fa-car"></i>
                        </div>
                    <h3 class="no-margin">{{$num_cars}}</h3>
                    <h3>اجمالي السيارات </h3>
                    </div>
                <span class="range-bar" style="width:15%"></span>                                     
                </div>
            </div>                                                                        
            <div class="col-lg-4">
                <div class="panel" style="background:#199EC7">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-bullhorn"></i>
                        </div>
                    <h3 class="no-margin">{{$num_open_orders}}</h3>
                    <h3>الرحلات المفتوحة</h3>
                    </div>
                <span class="range-bar" style="width:20%"></span>                                     
                </div>
            </div>            
            <div class="col-lg-4">
                <div class="panel" style="background:#199EC7">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-hourglass"></i>
                        </div>
                    <h3 class="no-margin">{{$num_inprogress_orders}}</h3>
                    <h3>رحلات قيد التنفيذ</h3>
                    </div>
                <span class="range-bar" style="width:50%"></span>                                     
                </div>
            </div>  
            <div class="col-lg-4">
                <div class="panel" style="background:#199EC7">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-saved"></i>
                        </div>
                    <h3 class="no-margin">{{$num_finished_orders}}</h3>
                    <h3>الرحلات المنتهية</h3>
                    </div>
                <span class="range-bar" style="width:10%"></span>                                     
                </div>
            </div>  
            <div class="col-lg-4">
                <div class="panel" style="background:#199EC7">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-minus-sign"></i>
                        </div>
                    <h3 class="no-margin">{{$num_closed_orders}}</h3>
                    <h3>الرحلات المغلقة</h3>
                    </div>
                <span class="range-bar" style="width:80%"></span>                                     
                </div>
            </div>              

            <div class="col-lg-4">
                <div class="panel" style="background:#FE8A71">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-envelope"></i>
                        </div>
                    <h3 class="no-margin">{{$num_contacts}}</h3>
                    <h3>الاقتراحات والشكاوى</h3>
                    </div>
                    <span class="range-bar" style="width:60%"></span>                                     
                </div>
            </div>           


        </div>
<!-- <div id = "container" style = "width: 550px; height: 400px; margin: 0 auto"></div> -->
<script language = "JavaScript">
//start map
       <?php $i = 0;?>
        var places = [
        <?php foreach($orders as $order){?>
            ["رقم الطلب : <?=$order->id;?> \n البداية: <?=$order->start_address.' \n النهاية: '.$order->end_address;?>", <?=$order->start_lat;?>, <?=$order->start_long?>, <?=$i;?>] ,
        <?php $i++; }?>
        ];
            function initMap() {
            // if (navigator.geolocation) {
            //     navigator.geolocation.getCurrentPosition(showPosition);
            // } else {
            //    innerHTML = " حدث خطا أتناء تحديد الموقع ";
            // }

            //     function showPosition(position) {
                   maplat = 24.774265;//position.coords.latitude;
                   maplng = 46.738586;//position.coords.longitude;

                    var myLatLng = { lat: maplat, lng: maplng };
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 6,
                        center: myLatLng ,
                        // disableDefaultUI: false,
                        mapTypeId: google.maps.MapTypeId.TERRAIN,
                    });
                    setMarkers(map);
                // }
            }
        
            function setMarkers(map) {
                var image = {
                    url: "{{url('img/marker.png')}}",
                    // size: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 0)
                };
                var shape = {
                    coords: [1, 1, 1, 20, 18, 20, 18, 1],
                    type: 'poly'
                };
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
                    var infowindow = new google.maps.InfoWindow({
                      content: place[0]
                    });
                    // google.maps.event.addListener(marker, 'click', function() {
                      // infowindow.open(map,marker);
                    // });

                    // marker.addListener('click', function() {
                    //  window.location.href = this.url;
                    // });
                }
            }
//end map
  $(document).ready(function() {
    <?php if($num_newcontacts > 0):?>
        setTimeout(function(){ Materialize.toast('لديك <?=$num_newcontacts;?> رسائل جديدة', 7000) }, 4000);
    <?php endif?> 
    <?php if($num_newUserMetas > 0):?>
        setTimeout(function(){ Materialize.toast('لديك <?=$num_newUserMetas;?> طلب عمل جديد', 7000) }, 4000);
    <?php endif?>       
         //flotchart1
            var chart = {
               plotBackgroundColor: null,
               plotBorderWidth: null,
               plotShadow: false
            };
            var title = {
               text: 'المستخدمين'   
            };
            var tooltip = {
               pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            };
            var plotOptions = {
               pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  
                  dataLabels: {
                     enabled: true,
                     format: '<b>{point.name}%</b>: {point.percentage:.1f}',
                     style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor)||
                        'black'
                     }
                  }
               }
            };
            var series = [{
               type: 'pie',
               name: '',
               data: [
                  ['اندرويد', {{$android_devices}} ],
                  ['ايفون',{{$ios_devices}} ]
               ]
            }];
            var json = {};   
            json.chart = chart; 
            json.title = title;     
            json.tooltip = tooltip;  
            json.series = series;
            json.plotOptions = plotOptions;
            $('#flotchart1').highcharts(json);  
          //end flotchart1

          //flotchart2
            var title = {
               text: 'الرحلات فى الأسبوع الأخير'   
            };
            var xAxis = {
               categories: ["<?=date('Y-m-d ',strtotime('yesterday'));?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24);?>", 
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*2);?>", 
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*3);?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*4);?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*5);?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*6);?>"
                           ]
            };
            var yAxis = {
               title: {
                  text: 'طلب'
               },
               plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
               }]
            };   
            var tooltip = {
               valueSuffix: ''
            }
            var legend = {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0
            };

            var series =  [{
                  name: 'الرحلات',
                  data: [<?=$day7;?>, <?=$day6;?>,<?=$day5;?>,<?=$day4;?>,<?=$day3;?>,<?=$day2;?>,<?=$day1;?>]
               }
            ];

            var json = {};
            json.title = title;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            json.legend = legend;
            json.series = series;
            $('#flotchart2').highcharts(json);
          //end flotchart2
          
          //flotchart3
            var title = {
               text: 'الأرباح فى الأسبوع الأخير'   
            };
            var xAxis = {
               categories: ["<?=date('Y-m-d ',strtotime('yesterday'));?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24);?>", 
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*2);?>", 
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*3);?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*4);?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*5);?>",
                            "<?=date('Y-m-d ',strtotime('yesterday')-60*60*24*6);?>"
                           ]
            };
            var yAxis = {
               title: {
                  text: 'المبلغ'
               },
               plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
               }]
            };   
            var tooltip = {
               valueSuffix: ' {{setting('site_currency_ar')}}'
            }
            var legend = {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0
            };

            var series =  [{
                  type: 'area',
                  name: 'الأرباح',
                  data: [<?=$profitday7;?>, <?=$profitday6;?>,<?=$profitday5;?>,<?=$profitday4;?>,<?=$profitday3;?>,<?=$profitday2;?>,<?=$profitday1;?>]
               }
            ];

            var json = {};
            json.title = title;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            json.legend = legend;
            json.series = series;
            $('#flotchart3').highcharts(json);          
          //end flotchart3

          //flotchart4
            var title = {
               text: 'عدد الرحلات حسب ساعات اليوم'   
            };
            var xAxis = {
               categories: ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23']
            };
            var yAxis = {
               title: {
                  text: 'عدد الرحلات'
               },
               plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
               }]
            };   
            var tooltip = {
               valueSuffix: ' طلب'
            }
            var legend = {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0
            };

            var series =  [{
                  type: 'area',
                  name: 'عدد الرحلات ',
                  data: [<?=$hour0;?>, <?=$hour1;?>,<?=$hour2;?>,<?=$hour3;?>,<?=$hour4;?>,<?=$hour5;?>,<?=$hour6;?>,
                         <?=$hour7;?>, <?=$hour8;?>,<?=$hour9;?>,<?=$hour10;?>,<?=$hour11;?>,<?=$hour12;?>,<?=$hour13;?>,
                         <?=$hour14;?>, <?=$hour15;?>,<?=$hour16;?>,<?=$hour17;?>,<?=$hour18;?>,<?=$hour19;?>,<?=$hour20;?>,
                         <?=$hour21;?>, <?=$hour22;?>,<?=$hour23;?>
                        ]
               }
            ];

            var json = {};
            json.title = title;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            json.legend = legend;
            json.series = series;
            $('#flotchart4').highcharts(json);          
          //end flotchart4          
});
      </script>
       



    </div>
</div>
<script src="{{asset('dashboard/plugins/materialize/js/materialize.min.js')}}"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=setting('google_places_key')?>&callback=initMap&language=ar"></script>
@endsection