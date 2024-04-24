@extends('apis_dashboard.layout.master')
    @section('title')
    الأحصائيات 
    @endsection
<link href="{{asset('dashboard/plugins/materialize/css/materialize.min.css')}}" rel="stylesheet" type="text/css">     
@section('content')

<div class="panel panel-flat second">
    <div class="panel-heading">
        <div class="row">
                
            <div class="col-lg-4">
                <div class="panel" style="background:#199EC7">
                    <div class="panel-body" style="height: 110px">
                        <div class="heading-elements myelements">
                          <i class="glyphicon glyphicon-bullhorn"></i>
                        </div>
                    <h3 class="no-margin">{{$num_open_orders}}</h3>
                    <h3>الطلبات الجديدة</h3>
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
                    <h3>الطلبات قيد التنفيذ</h3>
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
                    <h3>الطلبات المكتملة</h3>
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
                    <h3>الطلبات المغلقة</h3>
                    </div>
                <span class="range-bar" style="width:80%"></span>                                     
                </div>
            </div>              



        </div>
<script >

    $(document).ready(function() {
           // setTimeout(function(){ Materialize.toast('لديك 5 رسائل جديدة', 7000) }, 4000);
    });
    </script>
           

    </div>
</div>
<script src="{{asset('dashboard/plugins/materialize/js/materialize.min.js')}}"></script>
@endsection