@extends('layouts.paymentLayout')
@section('head')
    شحن المحفظة في تطبيق
@endsection
@section('content')
    <style>
        .wpwl-container{
            z-index: 99;
            color : rgb(15, 13, 13);
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
    <form action="{{route('visaTransferBalanceResult')}}" class="paymentWidgets" data-brands="VISA MASTER"></form>
    <script>
        var wpwlOptions = {
            locale: "ar",
        }
    </script>
        <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkoutId}}"></script>
@endsection