<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

      <title>{{trans('user.captain_performance')}}</title>
	<!-- Global stylesheets -->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
            /*  width: 160px;*/
            /*  height: 150px;*/
            /*  border-radius: 10px;*/
            /*}*/

            /*.info p {*/
            /*  font-size: 18px;*/
            /*  color: #191919;*/
            /*}*/

            /*.info p span {*/
            /*  font-size: 16px;*/
            /*  color: #053d60;*/
            /*}*/

            /*.SMS {*/
            /*  color: #053d60;*/
            /*  font-size: 16px;*/
            /*  cursor: pointer;*/
            /*}*/
            /*.d-flex{*/
            /*  display: flex;*/
            /*}*/
            /*.panel1{*/
            /*  justify-content: space-between;*/
            /*  align-items: center;*/
            /*  padding: 10px;*/
            /*}*/
            /*.cap a i{*/
            /*  color:#053d60;*/
            /*}*/
            /*.cap-info{*/
            /*  text-align: center;*/
            /*}*/
            /*.cap-info h3{*/
            /*  font-weight: bold;*/
            /*}*/
            /*.cap a{*/
            /*  display: flex;*/
            /*  flex-direction: column;*/
            /*  font-size: 19px;*/
            /*  justify-content: center;*/
            /*  text-align: center;*/
            /*  color:rgba(0,0,0,.55);*/
            /*}*/
            /*.panel2{*/
            /*  display: flex;*/
            /*  flex-direction: column;*/
            /*  justify-content: center;*/
            /*  text-align: center;*/
            /*  padding: 15px;*/
            /*}*/
            /*.panel2 i{*/
            /*  color:#053d60;*/
            /*  font-size: 40px;*/
            /*  margin-bottom: 10px;*/
            /*}*/
            /*.lgray{*/
            /*  color:#797979;                      */
            /*  font-size:16px;*/
            /*}*/
            /*.lgray+span{*/
            /*  font-size: 15px;*/
            /*}*/
            /*.info-item{*/
            /*  padding: 0 14px;*/
            /*  border-bottom: 1px solid #dadada;*/
            /*}*/
            /*.info-item .info{*/
            /*  display: flex;*/
            /*  justify-content: space-between;*/
            /*}*/
            /*.green{*/
            /*  color:#053d60!important;*/
            /*}*/
            /*.info-item .info span{*/
            /*  font-size: 16px;*/
            /*}*/
            /*.info-item:last-of-type{*/
            /*  border-bottom: 0*/
            /*}*/
            /*.info-item .info h3{*/
            /*  font-size: 16px;*/
            /*  font-weight: bold;*/
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
            .top .top-con{
                background-color:#02008f;
                padding: 10px 20px;
                border-radius: 8px;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: space-between;
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

            body{height:auto; padding-bottom:40px;}
            table{border-collapse: separate;}
            table thead{    background: #02008f;color:#fff;transform:translateY(-10px)}
            table thead tr th{text-align: center;padding:8px 0;}
            table tbody td{text-align: center;background:#02008f; padding: 10px 0;color:#fff;width:30%;border-bottom:1px solid #ddd;overflow:hidden:}
            table tbody td.after-co{position:relative;}
            table tbody td.color-w{background: #fff; color: #202020;}
            table tbody tr{box-shadow:0 2px 5px rgba(0,0,0,.07);}
            table tbody td.after-co::after{ content:''; position: absolute;top: 0; left: -44px ; height: 100%;border-color: transparent #02008f transparent transparent;border-style: solid;border-width: 22px}
        </style>

  </head>

<body> 
	        <div class="panel panel1 d-flex">
                <div class="cap d-flex">
                    <a href="#">
                        <!-- <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        <span></span> -->
                    </a>
                </div>
                <div class="cap-info">
                </div>
                <div class="cap d-flex">
                    <a href="#" onclick="window.history.go(-1); return false;">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>{{trans('user.back')}}</span>
                    </a>
                </div>
            </div>

		<div class="panel panel-flat">	
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th>{{trans('user.date')}}</th>
						<th>{{trans('user.num_rating')}} </th>
						<th>{{trans('user.average')}} </th>
					</tr>
				</thead>
				<tbody>
					@foreach($ratings as $rating)
						<tr>
							<td>{{date('d - m',strtotime($rating->date))}}</td>
							<td>{{$rating->count_ratings}}</td>
							<td>{{$rating->rate_average}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>
	

    </body> 
</html>    