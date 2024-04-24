@extends('apis_dashboard.layout.master')
    @section('title')
	 الاعدادات
	@endsection

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-body" style="font-size: large;">
				<div class="row">
					<div class="col-md-4">
						<p> <i class="glyphicon glyphicon-phone"> </i> اسم التطبيق : <span>  </span> </p>
						<p> <i class="fa fa-user"> </i> اسم العميل : <span>  </span> </p>
						<p> <i class="fa fa-phone"> </i> رقم الهاتف : <span>  </span> </p>
						<p> <i class="glyphicon glyphicon-envelope"> </i> البريد الالكتروني : <span>  </span> </p>
						<p> <i class="glyphicon glyphicon-cog"> </i> APP ID : <span>  </span> </p>
						<p> <i class="glyphicon glyphicon-cog"> </i> Server Key : <span>  </span> </p>

					</div>
					<div class="col-md-8">
						<p>   <span>  {{Auth::guard('externalAppTokens')->user()->app_name}}</span> </p>
						<p>   <span>  {{Auth::guard('externalAppTokens')->user()->client_name}}</span> </p>
						<p>   <span>  {{Auth::guard('externalAppTokens')->user()->phone}}</span> </p>
						<p>   <span>  {{Auth::guard('externalAppTokens')->user()->email}}</span> </p>
						<p>   <span>  {{Auth::guard('externalAppTokens')->user()->app_id}}</span> </p>
						<p>   <span>  {{Auth::guard('externalAppTokens')->user()->server_key}}</span> </p>


					</div>

					
				</div>	
				
			</div>
		</div>	
	</div>

</div>	

<!-- </div> -->

@endsection