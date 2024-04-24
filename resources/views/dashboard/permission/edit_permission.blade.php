@extends('dashboard.layout.master')
	@section('title')
	تعديل الصلاحيات
	@endsection
<!-- style -->
@section('style')

@endsection
<!-- /style -->

@section('content')



<div class="panel panel-flat custom-colomns"> 
	<div class="panel-body">
		<div class="row">
			<form action="{{route('updatepermission')}}" method="POST">

				{{csrf_field()}}
				<input type="hidden" name="id" value="{{$role->id}}">
				
				<div class="col-sm-11" style="margin-bottom: 20px">
					<input type="text" name="role_name" class="form-control" value="{{$role->role}}" required>
				</div>
					{{EditPermissions($role->id)}}
				<div class="col-sm-12">
					<button class="btn btn-success btn-block" type="submit">حفظ التعديلات</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- javascript -->
@section('script')
<script>

	// $("#checkAll").change(function(){
	// 	alert('sds')
	// 	var checked=$(this).prop('checked');
	// 	$("input[name='permissions[]']").prop('checked',checked);

	// });
</script>

@endsection
<!-- /javascript -->

@endsection