@extends('dashboard.layout.master')
	@section('title')
	اضافة صلاحيات
	@endsection
<!-- style -->
@section('style')
<style type="text/css">

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



<div class="panel panel-flat custom-colomns">
	<div class="panel-body">
		<div class="row">
			<form action="{{route('addpermission')}}" method="POST">
				{{csrf_field()}}
				<div class="col-sm-11" style="margin-bottom: 20px">
					<input type="text" name="role_name" class="form-control" placeholder="اسم الصلاحيه" required>
				</div>
					{{Permissions()}}
				<div class="col-sm-12">
					<button class="btn btn-success btn-block" type="submit">اضافه</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- javascript -->
@section('script')
<script>
	$("#checkAll").change(function(){
		alert('sds')
		var checked=$(this).prop('checked');
		$("input[name='permissions[]']").prop('checked',checked);

	});
</script>

@endsection
<!-- /javascript -->

@endsection