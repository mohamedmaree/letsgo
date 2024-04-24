@extends('dashboard.layout.master')
<!-- /style -->
	@section('title')
	الصفحات
	@endsection
@section('style')
    <!-- Include Editor style. -->
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/froala_style.min.css" rel="stylesheet" type="text/css" />
 
@endsection	
@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">

	<div class="panel-body">
		<div class="row">
			<div class="col-xs-3">
				<a href="#" class="btn bg-blue btn-block btn-float btn-float-lg " type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة صفحة</span></a>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الصفحات : {{count($pages)}} </span>  </button>			</div>
			<div class="col-xs-3">
				<button class="btn bg-teal-400 btn-block btn-float btn-float-lg reload" type="button"><i class="icon-reload-alt"></i> <span>تحديث الصفحه</span></button>			</div>
		</div>
	</div>	

			<div class="panel-heading">
				<h6 class="panel-title"></h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<?php $active = 'active';?>
						@foreach($pages as $page)
						<li class="{{$active}}"><a href="#basic-tab-{{$page->id}}" data-toggle="tab">{{$page->title_ar}} </a></li>
						<?php $active ='';?>
						@endforeach
					</ul>

					<div class="tab-content">
						<?php $active = 'active';?>
                        @foreach($pages as $page)
						<!-- copyright -->
						<div class="tab-pane {{$active}}" id="basic-tab-{{$page->id}}">
							<?php $active = '';?>
							<div class="col-md-12">
								<div class="panel panel-flat">
									<div class="panel-heading">
										<h5 class="panel-title"></h5>
										<div class="heading-elements">
											<ul class="icons-list">
						                		<li><a data-action="collapse"></a></li>
						                		<li><a data-action="reload"></a></li>
						                	</ul>
					                	</div>
									</div>
									<div class="panel-body">
										<form action="{{route('updatepage')}}" method="post" class="form-horizontal">
											{{csrf_field()}}
											<input type="hidden" name="pageid" value="{{$page->id}}"/>
											<div class="form-group">
												<div class="col-lg-12">
													<input type="text" placeholder="عنوان الصفحة بالعربية" name="title_ar" class="form-control" value="{{$page->title_ar}}"/>
												</div>
												<div class="col-lg-12">
													<input type="text" placeholder="عنوان الصفحة بالانجليزية" name="title_en" class="form-control" value="{{$page->title_en}}"/>
												</div>
											</div>
											<div class="form-group">
												<div class="col-lg-12">
													<textarea placeholder="محتوى الصفحة بالعرية" name="content_ar" id="edit_content_ar" class="form-control" rows="10">{{$page->content_ar}}</textarea>
												</div>
												<div class="col-lg-12">
													<textarea placeholder="محتوى الصفحة بالانجليزية" name="content_en" id="edit_content_en" class="form-control" rows="10">{{$page->content_en}}</textarea>
												</div>
											</div>

											<div class="text-left">
												<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
											</div>
										</form>
										<form action="{{route('deletepage')}}" method="POST" >
											{{csrf_field()}}
											<input type="hidden" name="id" value="{{$page->id}}">
											<button type="submit" class="btn btn-danger generalDelete">حذف الصفحة</button>
										</form>
									</div>
								</div>
							</div>
						</div>
						@endforeach

					</div>
				</div>
			</div>
		</div>



		<!-- Add Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">أضافة صفحة جديد</h5>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<form action="{{route('addpage')}}" method="POST" enctype="multipart/form-data">
		        		{{csrf_field()}}
						<div class="form-group">
							<div class="col-lg-12">
								<input type="text" placeholder="عنوان الصفحة بالعربية" name="title_ar" class="form-control" value="">
								<input type="text" placeholder="عنوان الصفحة بالانجليزية" name="title_en" class="form-control" value="">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-12">
								<label>
                                    محتوي الصفحة بالعربية: 
								</label>
								<textarea placeholder="محتوى الصفحة بالعربية" id="froala-editor" name="content_ar" class="form-control" rows="10"></textarea>
								<label>
                                    محتوي الصفحة بالانجليزية:
								</label>
								<textarea placeholder="محتوى الصفحة بالانجليزية" id="froala-editor" name="content_en" class="form-control" rows="10"></textarea>
							</div>
						</div>
				        <div class="col-sm-12" style="margin-top: 10px">
					      	<button type="submit" class="btn btn-primary addCategory">اضافه</button>
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				        </div>

		        	</form>
		        </div>
		      </div>

		    </div>
		  </div>
		</div>
		<!-- /Add Modal -->

	</div>
</div>


   
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
    <!-- Include Editor JS files. -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/js/froala_editor.pkgd.min.js"></script>
 
<script type="text/javascript">
  $(function() {
    $('textarea#froala-editor').froalaEditor();
  });

  $(function() {
    $('textarea#edit_content_ar').froalaEditor();
    $('textarea#edit_content_en').froalaEditor();
  });

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});	
        // ClassicEditor.create( document.querySelector( '.editor' ) ).catch( error => { console.error( error ); } );           
</script>

@endsection