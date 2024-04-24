<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

      <title>{{trans('user.captain_performance')}}</title>
	<!-- Global stylesheets -->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/bootstrap.css')}}"          rel="stylesheet" type="text/css">
	<link href="{{asset('dashboard/css/core.css')}}"               rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{asset('dashboard/js/core/libraries/jquery.min.js')}}"></script>
	<script src="{{asset('dashboard/js/core/libraries/bootstrap.min.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
	<style>
		/*body{*/
		/*    background-color: rgb(37 46 63);*/
		/*}*/
		/*.panel {*/
		/*    background-color: rgb(45 55 74);*/
		/*}*/
		/*.mainColor{*/
		/*    color:rgb(250 182 52)*/
		/*}*/
		/*.usermetaimg {*/
		/*	width: 160px;*/
		/*	height: 150px;*/
		/*	border-radius: 10px;*/
		/*}*/

		/*.info p {*/
		/*	font-size: 18px;*/
		/*	color: #191919;*/
		/*}*/

		/*.info p span {*/
		/*	font-size: 16px;*/
		/*	color: #053d60;*/
		/*}*/

		/*.SMS {*/
		/*	color: #053d60;*/
		/*	font-size: 16px;*/
		/*	cursor: pointer;*/
		/*}*/
		/*.d-flex{*/
		/*	display: flex;*/
		/*}*/
		/*.panel1{*/
		/*	justify-content: space-between;*/
		/*	align-items: center;*/
		/*	padding: 10px;*/
		/*}*/
		/*.cap a i{*/
		/*	color:#053d60;*/
		/*}*/
		/*.cap-info{*/
		/*	text-align: center;*/
		/*}*/
		/*.cap-info h3{*/
		/*	font-weight: bold;*/
		/*}*/
		/*.cap a{*/
		/*	display: flex;*/
		/*	flex-direction: column;*/
		/*	font-size: 19px;*/
		/*	justify-content: center;*/
		/*	text-align: center;*/
		/*	color:rgba(0,0,0,.55);*/
		/*}*/
		/*.panel2{*/
		/*	display: flex;*/
		/*	flex-direction: column;*/
		/*	justify-content: center;*/
		/*	text-align: center;*/
		/*	padding: 15px;*/
		/*}*/
		/*.panel2 i{*/
		/*	color:#053d60;*/
		/*	font-size: 40px;*/
		/*	margin-bottom: 10px;*/
		/*}*/
		/*.lgray{*/
		/*	color:#797979;    					*/
		/*	font-size:16px;*/
		/*}*/
		/*.lgray+span{*/
		/*	font-size: 15px;*/
		/*}*/
		/*.info-item{*/
		/*	padding: 0 14px;*/
		/*	border-bottom: 1px solid #dadada;*/
		/*}*/
		/*.info-item .info{*/
		/*	display: flex;*/
		/*	justify-content: space-between;*/
		/*}*/
		/*.green{*/
		/*	color:#053d60!important;*/
		/*}*/
		/*.info-item .info span{*/
		/*	font-size: 16px;*/
		/*}*/
		/*.info-item:last-of-type{*/
		/*	border-bottom: 0*/
		/*}*/
		/*.info-item .info h3{*/
		/*	font-size: 16px;*/
		/*	font-weight: bold;*/
		/*}*/

		/*.fa-chevron-right, .fa-chevron-left{*/
		/*    color: #333;*/
		/*    background: #fff;*/
		/*    border-radius: 50%;*/
		/*    padding: 4px;*/
		/*    width: 25px;*/
		/*    height: 25px;*/
		/*    font-size: 13px;*/
		/*    display: flex;*/
		/*    justify-content: center;*/
		/*    align-items: center;*/
		/*}*/
		/*.cap span{*/
		/*    color : #FAB634FF*/
		/*}*/
		/*.cap-info h3{color: #FAB634FF}*/
		/*.cap-info p{color : #fff}*/
		/*.nav-pills {*/
		/*    width: 100%;*/
		/*    display: flex;*/
		/*    justify-content: space-between;*/
		/*    align-items: baseline;*/
		/*}*/
		/*.nav-pills li{*/
		/*    width: calc(95%/3);*/

		/*}*/
		/*.nav-pills li:hover a{color : #333 !important;}*/
		/*.nav-pills a{*/
		/*    background-color: #2D374AFF;*/
		/*    color: #FAB634FF !important;*/
		/*    display: flex !important;*/
		/*    justify-content: center;*/
		/*    align-items: center;*/
		/*    height: 40px !important;*/
		/*    font-weight: bold !important;*/
		/*}*/

		/*.nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus{background-color:#FAB634FF ; color: #fff !important }*/
		/*.addiitoal{*/
		/*    display: flex;*/
		/*    justify-content: space-between;*/
		/*    align-items: center;*/
		/*    padding: 15px 10px;*/
		/*}*/
		/*.addiitoal span{color: #FAB634FF; font-weight: 600 }*/
		/*.total_price{*/
		/*    display: flex;*/
		/*    justify-content: space-between !important;*/
		/*    align-items: center;*/
		/*    padding: 15px 10px;*/

		/*}*/
		/*.total_price1{border-bottom: 1px solid #fff;}*/
		/*.total_price .total{color:  #fff ;font-weight: 500; font-size: 17px}*/
		/*.total_price .price { color : #FAB634FF; font-weight: 500 ; font-size: 17px}*/
		/*.total_price2{flex-direction: column !important ; align-items: start !important; justify-content: start !important;}*/
		/*.add-pr{display: flex ; justify-content: space-between ; align-items: center; width: 100%}*/
		/*.lgray+span{color: #fff}*/
		/*.lgray{*/
		/*    color: #FAB634FF !important*/
		/*}*/
		/*.info-item h3{color: #fff}*/
		/*.info-item .info span{ color: #FAB634FF  }*/
		/*.datatable-basic thead tr th{color: #FAB634FF}*/
		/*.datatable-basic tbody tr td{color: #fff}*/
		/*.datatable-basic tbody tr td a{color: #FAB634FF}*/

		.top-title-chart .head  {
			font-size: 25px;
		}

		.span-font {
			font-size: 20px;
			font-weight: bold;
		}

		@media (max-width: 550px) {
			.res-mb {
				margin-bottom: 30px;
			}
		}

		.top-hint-arrow .head {
			padding: 0 12px;
		}


		.details-menu {
			transform: translateY(150%);
			transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
			z-index: 5;
			background: #fff;
		}

		.details-menu.show {
			transform: translateY(0);
		}

		.details-menu::-webkit-scrollbar {
			width: 6px;
			height: 6px;
			transition: 0.3s;
		}
		@media (max-width: 768px) {
			.details-menu::-webkit-scrollbar {
				height: 1px;
			}
		}
		.details-menu::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 3px;
		}
		.details-menu::-webkit-scrollbar-thumb {
			background-image: linear-gradient(to right, #1799c6, #099f95);
			border-radius: 3px;
		}
		.details-menu::-webkit-scrollbar-thumb:hover {
			background: #555;
		}




		body {
			background-color: #fff;
		}
		html::-webkit-scrollbar {
			width: 10px;
		}

		html::-webkit-scrollbar-track {
			background: transparent;
		}

		html::-webkit-scrollbar-thumb {
			border-radius: 25px;
			background-color: #02008f91;
		}
		.top{
			margin-top: 20px;
			margin-bottom: 20px;
		}
		/*.top .top-con{*/
		/*	!*background-color:#02008f;*!*/
		/*	padding: 10px 20px;*/
		/*	border-radius: 8px;*/
		/*	color: #fff;*/
		/*	display: flex;*/
		/*	align-items: center;*/
		/*	justify-content: center;*/
		/*}*/

		.hint-item {
			display: flex;
			gap: 5px;
		}

		.hint-item p {
			margin-bottom: 0;
		}

		.hint-item i {
			width: 20px;
			height: 20px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			background-color: green;
			color: #fff;
			font-size: 12px;
		}

		.top-con{
			padding: 10px 20px;
			border-radius: 8px;
			color: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.top-title {
			color: #000;
			text-align: center;
		}

		.top .top-c{
			text-align: center;
		}

		.top .top-c h3 {
			margin-top: 0;
			font-size: 22px;
			margin-bottom: 8px;
		}

		.top .top-l, .top .top-r{
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		.top .top-l span, .top .top-r span{
			margin-top: 8px;
			font-size: 16px;
		}

		.cards .cards-con{
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
			gap: 20px 20px;
		}

		.cards .cards-con .card{
			box-shadow:0 2px 5px rgba(0,0,0,.07); display:flex;
			flex-direction: column;
			justify-content: space-between;
			align-items: center;
			/*background: #2d8bfa;*/
			border-radius: 8px;
			padding: 12px 0;
		}

		.cards .card-c{
			background: #02008f;
			color: #fff;
		}

		.cards .card-r{
			background: #fff;
			color: #2d8bfa;
		}

		.cards .card-l{
			background: #fff;
			color: #13d47a;
		}

		.cards .card .icon i{
			font-size: 26px;
		}

		.cards .card-c .icon i{
			color: #4ABF60;
		}
		.cards .card .text{
			font-size: 17px;
			margin: 8px 0;
			font-weight: 700;
		}

		.cards .card .num{
			font-size: 17px;
			font-weight: 600;
			letter-spacing: .8;
		}

		.incentive{
			margin-top: 20px;
		}

		.incentive .incentive-con{
			background: #02008f;
			padding: 13px 25px;
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			color: #fff;
		}

		.incentive .incentive-con .inc-text{
			font-size: 18px;
			color: #fff;
		}

		.boxs{
			margin-top: 20px;
			margin-bottom: 40px;
		}

		.boxs .box{
			margin-bottom: 10px;
			border-radius: 5px;
			background: #fff;
			margin-left: 0;
			margin-right: 0;
			display: flex;
			align-items: center;  box-shadow:0 2px 5px rgba(0,0,0,.07);
		}
		.hint-title {
			font-size: 14px;
			margin-bottom: 0;
		}
		.mb-3 {
			margin-bottom: 15px;
		}

		.boxs .box .box-text{
			padding: 10px 20px;
			background: #02008f;
			color: #fff;
			display: flex;
			justify-content: space-between;
			align-items: center;
			position: relative;
			font-size: 16px;
		}

		.boxs .box .box-text::after{
			content: '';
			position: absolute;
			top: 0;
			left: -44px;
			height: 100%;
			border-width: 22px;
			border-style: solid;
			border-color: transparent #02008f transparent transparent;
		}

		.boxs .box .box-salary{
			padding: 0 20px;
			text-align: left;
			direction: ltr;
			font-size: 16px;
			font-weight: bold;
			color: #202020;
		}

		.inner-chart {
			display: flex;
			justify-content: space-between;
			align-items: end;
			position: relative;
		}

		.top-title-chart {
			display: flex;
			width: auto;
			margin: 0 auto;
			flex-direction: column;
			position: relative;
		}

		.my-acount {
			position: absolute;
			left: 0;
			bottom: 0;
		}

		.db-acount .dropdown-item {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 10px;
			margin-bottom: 10px;
			border: 2px solid transparent;
			border-radius: 20px;
			background: #dddddd9e;
			font-size: 15px;
		}

		.dropdown-item.red {
			border-color: red;
		}

		.dropdown-item.black {
			border-color: #000;
		}

		.dropdown-item.green {
			border-color: green;
		}

		.db-acount .dropdown-item p {
			margin-bottom: 0;
		}

		.main-item:not(:last-child) {
			margin-bottom: 15px;
		}

		.details-menu {
			position: fixed;
			bottom: 2px;
			left: 15px;
			right: 15px;
			height: 50%;
			border-top-left-radius: 10px;
			border-top-right-radius: 10px;
			box-shadow: 0px 0px 3px #949494;
			padding: 15px;
			overflow-y: auto;
		}

		.btn-acount {
			display: flex;
			align-items: center;
			gap: 10px;
			font-size: 18px;
			cursor: pointer;
		}

		.btn-acount i {
			transition: .3s;
		}
		.btn-acount i.show {
			transform: rotate(180deg);
		}

		@media (max-width: 550px) {
			.inner-chart {
				flex-direction: column;
			}

			#chart {
				/*min-height: 280px !important;*/
				/*min-height: 280px !important;*/
			}

		}

		#chart {
			width: 100%;
			margin: 35px auto;
			box-shadow: 0px 0px 3px #949494;
			border-radius: 6px;
			padding: 20px;
		}

	/*	@media (max-width: 550px) {
			#chart {
				min-height: 280px !important;
			}
		}*/

		@media(max-width: 550px) {
			#chart {
				/*min-height: 250px !important;*/
				/*height: 250px !important;*/
			}
		}

		.box-details {
			padding: 8px;
			box-shadow: 0px 0px 3px #949494;
			border-radius: 6px;
			margin-bottom: 25px;
			display: flex;
			min-height: 70px;
		}

		.box-details i {
			width: 25px;
			height: 25px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			color: #fff;
			font-size: 15px;
			flex-shrink: 0;
			margin-inline-end: 10px;
		}

		.box-details i.blue {
			background-color: blue;
		}

		.box-details i.purple {
			background-color: purple;
		}

		.box-details i.red {
			background-color: #9d2323;
		}
		.box-details i.yellow {
			background-color: #efef3c;
		}

		.box-details i.green {
			background-color: green;
		}

		.box-details .title-details p {
			margin-bottom: 0;
			font-size: 16px;
			word-break: break-all;
		}

		.title-details {
			text-align: start;
		}

		@media(max-width: 550px) {
			.box-details .title-details p {
				font-size: 12px;
			}
		}

		.apexcharts-bar-area:hover {
		  fill: #0b6cf4;
		}

		.top-hint-arrow {
			position: relative;
		}

		.top-title .arrow-parent {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 100%;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.arrow-parent a {
			width: 30px;
			height: 30px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 50%;
			background: #5555f0;
			position: absolute;
			color: #fff;
			font-size: 21px;
		}

		.arrow-parent .right {
			right: -25px;
		}

		.arrow-parent .left {
			left: -25px;
		}


		body{height:auto; padding-bottom:40px;}
		table{border-collapse: separate;}
		table thead{	background: #02008f;color:#fff;transform:translateY(-10px)}
		table thead tr th{text-align: center;padding:8px 0;}
		table tbody td{text-align: center;background:#02008f; padding: 10px 0;color:#fff;width:30%;border-bottom:1px solid #ddd;overflow:hidden}
		table tbody td.after-co{position:relative;}
		table tbody td.color-w{background: #fff; color: #202020;}
		table tbody tr{box-shadow:0 2px 5px rgba(0,0,0,.07);}
		table tbody td.after-co::after{ content:''; position: absolute;top: 0; left: -44px ; height: 100%;border-color: transparent #02008f transparent transparent;border-style: solid;border-width: 22px}
	</style>

  </head>
    <body>
		<!--
		<div class="top">
			<div class="container">
				<div class="top-con">
					<div class="top-r">
                    <a href="{{url('captainPerformance/'.$user->id.'/'.($page+1).'/'.$lang)}}">
						<i class="fa fa-chevron-right" aria-hidden="true"></i>
						<span class="txt">{{trans('user.oldest')}}</span>
                    </a>
					</div>
					<div class="top-c">
						<h3 class="head">{{$user->name}}</h3>
						<p>{{$new_start_date}} - {{$new_end_date}} </p>
                        @if($captainMoneystatus == 'finished')
                            <p>
                            ( {{trans('user.finished')}}<i class=" glyphicon glyphicon-ok-sign green"></i> )
                            </p>
                        @endif
					</div>
					<div class="top-l">
                    <?php $newpage = ($page <= 1)? 1 : $page-1; ?>
                    @if($page > 1)
                    <a href="{{url('captainPerformance/'.$user->id.'/'.$newpage.'/'.$lang)}}">
						<i class="fa fa-chevron-left" aria-hidden="true"></i>
						<span class="txt">{{trans('user.latest')}}</span>
                    @endif
                    </a>
					</div>

				</div>
			</div>
		</div>
		-->

{{--		<div class="cards">--}}
{{--			<div class="container">--}}
{{--				<div class="cards-con">--}}
{{--					<div class="card card-r">--}}
{{--						<div class="icon">--}}
{{--                            <a href="{{url('captainAvailableTimes/'.$user->id.'/'.$page.'/'.$lang)}}">--}}
{{--							    <i class="fa fa-clock-o"></i>--}}
{{--                            </a>--}}
{{--						</div>--}}
{{--						<span class="text">{{trans('user.available_hours')}}</span>--}}
{{--						<span class="num">{{$totalAvailableHours}}</span>--}}
{{--					</div>--}}
{{--					<div class="card card-c">--}}
{{--						<div class="icon">--}}
{{--                            <a href="{{url('captainOrdersRatings/'.$user->id.'/'.$page.'/'.$lang)}}">--}}
{{--    							<i class="fa fa-star" aria-hidden="true"></i>--}}
{{--                            </a>--}}
{{--						</div>--}}
{{--						<span class="text">{{trans('user.rating')}}</span>--}}
{{--						<span class="num">{{$totalRate}}</span>--}}
{{--					</div>--}}
{{--					<div class="card card-l">--}}
{{--						<div class="icon">--}}
{{--                            <a href="{{url('captainOrdersAcceptance/'.$user->id.'/'.$page.'/'.$lang)}}">--}}
{{--    							<i class="fa fa-thumbs-up" aria-hidden="true"></i>--}}
{{--                            </a>--}}
{{--						</div>--}}
{{--						<span class="text">{{trans('user.acceptance_rate')}}</span>--}}
{{--						<span class="num">{{$totalAccept}}</span>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}

<!-- 		<div class="incentive">
			<div class="container">
				<div class="incentive-con">
					<span class="inc-text">الحوافز</span>
					<i class="fa fa-chevron-left" aria-hidden="true"></i>
				</div>
			</div>
		</div> -->


{{--		<div class="boxs">--}}
{{--			<div class="container">--}}
{{--				<div class="boxs-con">--}}
{{--					<div class="box row">--}}
{{--						<span class="box-text col-xs-6">{{trans('user.num_trips')}} <span>{{$num_orders}}</span></span>--}}
{{--						<span class="box-salary col-xs-6">{{$totalPrices_of_orders.' '.$currency}}</span>--}}
{{--					</div>--}}
{{--					<div class="box row">--}}
{{--						<span class="box-text col-xs-6">{{trans('user.rewards')}} <span>{{$num_rewards_orders}}</span></span>--}}
{{--						<span class="box-salary col-xs-6">{{$totalPrices_of_rewards_orders.' '.$currency}}</span>--}}
{{--					</div>--}}
{{--					<div class="box row">--}}
{{--						<span class="box-text col-xs-6">{{trans('user.closed_orders')}} <span>{{$num_withdraw_orders}}</span></span>--}}
{{--						<span class="box-salary col-xs-6">{{$totalPrices_of_withdraw_orders.' '.$currency}}</span>--}}
{{--					</div>--}}
{{--					<div class="box row">--}}
{{--						<span class="box-text col-xs-6">{{trans('user.guarantees')}} <span>{{$num_guarantee_orders}}</span></span>--}}
{{--						<span class="box-salary col-xs-6">{{$totalPrices_of_guarantee_orders.' '.$currency}}</span>--}}
{{--					</div>--}}
{{--					<div class="box row">--}}
{{--						<span class="box-text col-xs-6">{{trans('user.cash_trip_fees')}} <span>{{$num_cash_orders}}</span></span>--}}
{{--						<span class="box-salary col-xs-6">{{$totalPrices_of_cash_orders.' '.$currency}}</span>--}}
{{--					</div>--}}
{{--					<div class="box row">--}}
{{--						<span class="box-text col-xs-6" style="font-weight: bold">{{trans('user.total')}}</span>--}}
{{--						<span class="box-salary col-xs-6">--}}
{{--                        @if(floatval($total) >= 0)--}}
{{--                            {{$total.' '.$currency}}--}}
{{--                        @else--}}
{{--                            - {{$total.' '.$currency}}--}}
{{--                        @endif--}}
{{--                        </span>--}}
{{--					</div>--}}
{{--                    @if($captainMoneystatus == 'finished')--}}
{{--                    <div class="box row">--}}
{{--                        <span class="box-text col-xs-6">{{trans('user.amount_paid')}} {{($captainMoney->type == 'pay')? trans('user.from_captain'): trans('user.from_app')}}</span>--}}
{{--                        <span class="box-salary col-xs-6">{{$captainMoney->amount.' '.$captainMoney->currency}}</span>--}}
{{--                    </div>--}}
{{--                    @endif--}}

{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}

{{--		<table class="container">--}}
{{--			<div class="table">--}}
{{--				<thead>--}}
{{--					<tr>--}}
{{--                        <th>{{trans('user.trip_num')}}</th>--}}
{{--                        <th>{{trans('user.price')}}</th>--}}
{{--                        <th>{{trans('user.trip_date')}} </th>--}}
{{--                        <th>{{trans('user.details')}}</th>--}}
{{--					</tr>--}}
{{--				</thead>--}}
{{--                <tbody>--}}
{{--                        @foreach($orders as $order)--}}
{{--                            <tr>--}}
{{--                                <td>{{$order->id}}</td>--}}
{{--                                <td>{{$order->price}} {{$order->currency_ar}}</td>--}}
{{--                                <td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>--}}
{{--                                <td><a href="{{url('captainShowOrder/'.$order->id.'/'.$lang)}}">{{trans('user.view')}}</a></td>             --}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--				</tbody>--}}
{{--			</div>--}}
{{--		</table>--}}

		<div class="container">
			<div class="chart-content">

				<div class="inner-chart">

					<div class="top-title-chart">
						<div class="top-con">
							<div class="top-title top-hint-arrow">

								<div class="arrow-parent">

									<a href="{{url('captainPerformance/'.$user->id.'/'.($page+1).'/'.$lang)}}" class="right">
										<i class="fa fa-angle-double-right" aria-hidden="true"></i>
										{{--										<span class="txt">{{trans('user.oldest')}}</span>--}}
									</a>

									<?php $newpage = ($page <= 1)? 1 : $page-1; ?>
									@if($page > 1)
										<a href="{{url('captainPerformance/'.$user->id.'/'.$newpage.'/'.$lang)}}" class="left">
											<i class="fa fa-angle-double-left" aria-hidden="true"></i>

											{{--											<span class="txt">{{trans('user.latest')}}</span>--}}
											@endif
										</a>

								</div>


								<h3 class="head">تقرير الاسبوع</h3>
								<p class="hint-title">{{$new_start_date}} - {{$new_end_date}}</p>
							</div>


						</div>
						<div class="res-mb" style="flex-direction: column; text-align: center">
							<h3 class="head">صافى ارباحك لهذا الاسبوع</h3>
							<p class="center-title hint-title">
								<span class="span-font">{{$total.' '.$currency}}</span>
								
							</p>
						</div>

					</div>
					<div class="my-acount">
						<div class="dropdown db-acount">
							<div class="btn-acount">
								تفاصيل أكثر
								<i class="fa fa-angle-down" aria-hidden="true"></i>
							</div>
							<div class="details-menu">
								<div class="main-item">
									<div class="dropdown-item black">
										<p class="right">إجمالى الدخل</p>
										<p class="left">{{round($totalPrices_of_orders + $app_commission + $total_vat + $total_wasl,2).' '.$currency}}</p>
									</div>
								</div>

								<div class="main-item">
									<div class="dropdown-item red">
										<p class="right">حصة التطبيق</p>
										<p class="left">{{$app_commission.' '.$currency}}</p>
									</div>
								</div>

								<div class="main-item">
									<div class="dropdown-item red">
										<p class="right">الضريبة المضافة</p>
										<p class="left">{{$total_vat.' '.$currency}}</p>
									</div>
									<div class="hint-item">
										<p class="text" style="font-size: 10px;"> تم تحصيلها من العميل ،هذا الخصم لا يؤثر علي أرباحك</p>
										<i class="fa fa-check" aria-hidden="true" style="width:17px !important ;height:17px !important;"></i>
									</div>
								</div>

								<div class="main-item">
									<div class="dropdown-item red">
										<p class="right">الرسوم الحكومية</p>
										<p class="left">{{$total_wasl.' '.$currency}}</p>
									</div>
									<div class="hint-item">
										<p class="text" style="font-size: 10px;"> تم تحصيلها من العميل ،هذا الخصم لا يؤثر علي أرباحك</p>
										<i class="fa fa-check" aria-hidden="true" style="width:17px !important ;height:17px !important;"></i>
									</div>
								</div>

								<div class="main-item">
									<div class="dropdown-item green">
										<p class="right">صافي ارباحك</p>
										<p class="left">{{$total.' '.$currency}}</p>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="" id="chart">
				</div>

				<div class="container">
					<div class="details-box">
						<div class="row">
							<div class="col-6">
								<div class="box-details">
									<i class="fa fa-map-marker blue" aria-hidden="true"></i>
									<div class="title-details">
										<p>عدد الرحلات</p>
										<p>{{$num_orders}}</p>
									</div>
								</div>
							</div>

							<div class="col-6">
								<div class="box-details">
									<i class="fa fa-star-o purple" aria-hidden="true"></i>
									<div class="title-details">
										<p>متوسط التقييم</p>
										<p>{{$totalRate}}</p>
									</div>
								</div>
							</div>

							<div class="col-6">
								<div class="box-details">
									<i class="fa fa-angle-double-down red" aria-hidden="true"></i>
									<div class="title-details">
										<p style="white-space: nowrap; overflow: hidden; text-overflow: clip;">متوسط ربح الرحلة</p>
										<p>{{$day_profits_average.' '.$currency}}</p>
									</div>
								</div>
							</div>

							<div class="col-6">
								<div class="box-details">
									<i class="fa fa-circle-o-notch blue" aria-hidden="true"></i>

									<div class="title-details">
										<p>نسبة القبول</p>
										<p>{{$totalAccept}}</p>
									</div>
								</div>
							</div>

							<div class="col-6">
								<div class="box-details">
									<i class="fa fa-clock-o yellow" aria-hidden="true"></i>
									<div class="title-details">
										<p>ساعات النشاط</p>
										<p>{{$totalAvailableHours}}</p>
									</div>
								</div>
							</div>

							<div class="col-6">
								<div class="box-details">
									<i class="fa {{($growth_rate > 0)? 'fa-arrow-circle-up green': 'fa-arrow-circle-down red' }}" aria-hidden="true"></i>
									<div class="title-details">
										<p>معدل النمو</p>
										<p>%{{$growth_rate}}
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

    </body> 
</html>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

{{-- event show details --}}
<script>
	$(document).ready(function () {
		$(".btn-acount").on("click", function () {
			$(".details-menu").toggleClass("show");
			$(".btn-acount i").toggleClass("show");
		});
	})
</script>

{{--js chart plugin--}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.37.1/apexcharts.min.js"></script>

<script>
	var options = {
		colors: ['#3737ed'],
            states: {

                hover: {
                    filter: {
                        type: 'none',

                    }
                }
            },

		series: [{
			name: 'الربح',
			data: @json($days_values)
		}],
		chart: {
			height: 250,
			type: 'bar',
		},
		plotOptions: {
			bar: {
				borderRadius: 3,
				columnWidth: '35%',
				dataLabels: {
					position: 'top',
				},
			}
		},
		dataLabels: {
			enabled: true,
			formatter: function (val) {
				return val ;
			},
			offsetY: -20,
			style: {
				fontSize: '12px',
				colors: ["#304758"]
			}
		},

		xaxis: {
			categories: @json($days_names),
			// position: 'top',
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			},
			crosshairs: {
				fill: {
					type: 'gradient',
					gradient: {
						colorFrom: '#D8E3F0',
						colorTo: '#BED1E6',
						stops: [0, 100],
						opacityFrom: 0.4,
						opacityTo: 0.5,
					}
				}
			},
			tooltip: {
				enabled: true,
			}
		},
		yaxis: {
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false,
			},
			labels: {
				show: false,
				formatter: function (val) {
					return val +' ر.س';
				}
			}

		},
	};

	var chart = new ApexCharts(document.querySelector("#chart"), options);
	chart.render();
</script>