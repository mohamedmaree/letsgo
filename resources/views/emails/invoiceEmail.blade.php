<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mail</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Roboto', sans-serif;
        }
        .logo {
            display: block;
            margin: auto;
            height: 100px;
            width: 320px;
        }

        .total {
            font-size: 25px;
            font-weight: bold;
            background: #2bb77387;
            width: fit-content;
            padding: 10px;
            text-transform: capitalize;
            margin: 15px auto;
        }

        p.thx {
            text-transform: capitalize;
        }

        p.thx,
        p.plz {
            font-size: 18px;
            text-align: center;
        }

        p.thx span {
            color: #2cb573;
            font-weight: bold;
        }
        .details{
            padding: 0;
            list-style: none;
            width: 320px;
            margin-top: 20px;
            margin: auto;
        }
        .details li{
            display: flex;
            justify-content: space-between;
        }
        .details li{
            font-weight: bold;
            font-size: 17px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <img class="logo" src="{{ $message->embed(asset('/dashboard/uploads/setting/site_logo/logo.png')) }}" alt="">
    <div class="total">
        <span>{{trans('order.total_price')}} : {{$paid_cash}} </span>
    </div>
    <p class="thx">
        {{trans('order.thankstouse')}} <span>{{setting('site_title')}}</span>
    </p>
    <p class="plz">
       {{trans('order.tripdetails')}}
    </p>
    <ul class="details">
        <li>
            <span>{{trans('order.tripprice')}} :</span>
            <span>{{$tripprice}}</span>
        </li>
        <li>
            <span>{{trans('order.discount')}} :</span>
            <span>{{$discount}}</span>
        </li>
        <li>
            <span>{{trans('order.cashprice') }}:</span>
            <span>{{$requiredcash}}</span>
        </li>
    </ul>
</body>

</html>