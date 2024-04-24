@extends('layouts.paymentLayout')
@section('head')
    شحن المحفظة في تطبيق
@endsection
@section('content')
    <style>
        .wpwl-container{
            z-index: 99;
        }
        .wpwl-control, .wpwl-group-registration{
            color:#333;
        }
        .pay-link{
            position: relative;
            z-index: 99;
        }
        .pay-link ul{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pay-link ul li{
            margin: 10px;
        }
        .pay-link ul li img{
            width: 120px;
            height: 50px;
            max-width: 100%;
        }
    </style>
    <div class="pay-link">
        <ul>
                <!-- <li>
                    <a href="{{route('visaTransferBalance',['amount'=>$amount])}}">
                        <img src="{{url('img/vm.png')}}" alt="">
                    </a>
                </li> -->
                <li>
                    <a href="{{route('madaTransferBalance',['amount'=>$amount])}}">
                        <img src="{{url('img/mada.png')}}" alt="">
                    </a>
                </li>
                <!--  <li>
                    <a href="{{route('stcTransferBalance',['amount'=>$amount])}}">
                        <img src="{{url('img/stc.png')}}" alt="">
                    </a>
                </li> -->
                <li>
                    <a href="{{route('appleTransferBalance',['amount'=>$amount])}}">
                        <img src="{{url('img/apple.png')}}" alt="">
                    </a>
                </li> 

        </ul>
    </div>
@endsection