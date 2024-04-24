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

        .wpwl-apple-pay-button{-webkit-appearance: -apple-pay-button !important;}

    </style>
    <form action="{{route('appleTransferBalanceResult')}}" class="paymentWidgets" data-brands="APPLEPAY"></form>

<script>
var wpwlOptions = {
  paymentTarget:"_top",
  applePay: {
    displayName: "MyStore",
    total: { label: "COMPANY, INC." },
    supportedNetworks: ["masterCard", "visa", "mada"],
    supportedCountries: ["SA"]
  }
}
</script>
        <script src="https://oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkoutId}}"></script>
@endsection