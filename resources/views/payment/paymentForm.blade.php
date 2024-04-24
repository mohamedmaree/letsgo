@extends('layouts.paymentLayout')
@section('content')
<style type="text/css">
.wpwl-container{
  z-index : 999;
  color : rgb(15, 13, 13);
}
</style>
<script>
    var wpwlOptions = {
        locale: "ar",
    }
// var wpwlOptions = {
//     locale: "ar",
//     iframeStyles: {
//         'card-number-placeholder': {
//             'color': '#ff0000',
//             'font-size': '16px',
//             'font-family': 'monospace'
//         },
//             'cvv-placeholder': {
//             'color': '#0000ff',
//                 'font-size': '16px',
//                 'font-family': 'Arial'
//         }
//     }
// }
</script>
<!--
<script src="https://oppwa.com/v1/paymentWidgets.js?checkoutId={{$responseData->id}}"></script>
-->
<script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkoutId}}"></script>

<form action="{{route('paymentResponse',[$responseData->id])}}" class="paymentWidgets" data-brands="VISA MASTER MADA STC_PAY">
</form>


@endsection