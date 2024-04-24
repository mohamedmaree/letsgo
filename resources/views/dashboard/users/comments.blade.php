@extends('dashboard.layout.master')
	@section('title')
	التعليقات
	@endsection
<!-- style -->
@section('style')
@endsection
<!-- /style -->
@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">التعليقات علي {{$user->name}}</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<!-- supervisors reports  -->
						<li class="active"><a href="#basic-tab1" data-toggle="tab">التعليقات</a></li>
						<!-- users reports -->
						<!-- <li><a href="#basic-tab1" data-toggle="tab">تقارير الاعضاء</a></li> -->
					</ul>

					<div class="tab-content">

						<!-- users reports -->
						<div class="tab-pane active" id="basic-tab1">
							<table class="table text-nowrap">
								<thead>

									<tr>
										<th style="width: 50px">الوقت</th>
										<th style="width: 300px;">العضو</th>
										<th>التعليق</th>
										<th>التقييم</th>
										<th>التحكم</th>
									</tr>
								</thead>
								<tbody>
									@foreach($comments as $comment)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{$comment->created_at->diffForHumans()}}</small></h6>
											</td>
											<td>
												<div class="media-left media-middle">
													<span class="btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class="img-circle" src="{{asset('img/user/'.$comment->user->avatar)}}">
													</span>
												</div>

												<div class="media-body">
													<a href="#" class="display-inline-block text-default text-semibold letter-icon-title">{{$comment->user->name}}</a>
													<div class="text-muted text-size-small"><span class="status-mark border-coral position-left"></span>عضو</div>
												</div>
											</td>
											<td>
												<span class="text-semibold">{{$comment->comment}}</span>
											</td>
											<td>
												<span class="text-semibold">{{$comment->rate}}</span>
											</td>
											<td>
											<ul class="icons-list">
												<li>
													<form action="{{route('deleteComment')}}" method="POST">
														{{csrf_field()}}
														<input type="hidden" name="id" value="{{$comment->id}}">
														<li><button type="submit" class="generalDelete reset" title="حذف"><i class="icon-trash"></i></button></li>
													</form>
												</li>
											</ul>
											</td>

										</tr>
									@endforeach
								</tbody>
									<tr>
										<!-- pagination -->
										<td>{{$comments->links()}}</td>
									</tr>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});	
</script>


@endsection