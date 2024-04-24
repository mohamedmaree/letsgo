@extends('dashboard.layout.master')
	@section('title')
	الصلاحيات
	@endsection
<!-- style -->
@section('style')
		<style type="text/css">
			.modal .icon-camera
			{
				font-size: 100px;
				color: #797979
			}

			.modal input
			{
				margin-bottom: 4px
			}

			.dropdown-menu
			{
				min-width: 88px;
			}

			#hidden
			{
				display: none;
			}

			.reset
			{
				border:none;
				background: #fff;
    			margin-right: 11px;
			}

			.icon-trash
			{
				margin-left: 8px;
    			color: red;
			}
		</style>
@endsection
<!-- /style -->

@section('content')



<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة الصلاحيات</h5>
		<div class="heading-elements">
			<ul class="icons-list">
<!--         		<li><a data-action="collapse"></a></li>
        		<li><a data-action="reload"></a></li> -->
        		<!-- <li><a data-action="close"></a></li> -->
        	</ul>
    	</div>
	</div>

	<!-- buttons -->
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-3">
				<a href="{{route('addpermissionspage')}}" class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button"><i class="icon-plus3"></i> <span>اضافة صلاحيه</span></a>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الصلاحيات : {{count($roles)}} </span>  </button>			</div>
			<div class="col-xs-3">
				<button class="btn bg-teal-400 btn-block btn-float btn-float-lg reload" type="button"><i class="icon-reload-alt"></i> <span>تحديث الصفحه</span></button>			</div>
			<div class="col-xs-3">
				<a href="{{route('logout')}}" class="btn bg-warning-400 btn-block btn-float btn-float-lg" type="button"><i class="icon-switch"></i> <span>خروج</span></a>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم</th>
				<th>التاريخ</th>
                <th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($roles as $r)
				<tr>
					<td>{{$r->role}}</td>
					<td>{{$r->created_at->diffForHumans()}}</td>
					<td>
						<ul class="icons-list">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-menu9"></i>
								</a>
	                
								<ul class="dropdown-menu dropdown-menu-right">
									<li>
										<a href="{{route('editpermissionpage',$r->id)}}">
										<i class="icon-pencil7"></i>تعديل
										</a>
									</li>
					            @if($r->id != 0 && $r->id != 1)
									<form action="{{route('deletepermission')}}" method="POST">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$r->id}}">
										<li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>
									</form>
					            @endif
								</ul>
							</li>
						</ul>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</div>


<!-- javascript -->
@section('script')
<script>
	$("#checkAll").change(function(){
		alert('sds')
		var checked=$(this).prop('checked');
		$("input[name='permissions[]']").prop('checked',checked);

	});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});	
</script>

@endsection
<!-- /javascript -->

@endsection