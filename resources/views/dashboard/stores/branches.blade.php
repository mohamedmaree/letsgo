@extends('dashboard.layout.master')
@section('title')
	فروع {{$store->name_ar}}
@endsection
@section('style')
	<style>
		#infowindow-content {
			display: none;
		}

		#store_map #infowindow-content {
			display: inline;
		}

		#edit_store_map #infowindow-content {
			display: inline;
		}

		.pac-card {
			margin: 10px 10px 0 0;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			background-color: #fff;
			font-family: Roboto;
		}

		#pac-container {
			padding-bottom: 12px;
			margin-right: 12px;
		}

		.pac-container {
			z-index: 9999999;
		}

		.pac-controls {
			display: inline-block;
			padding: 5px 11px;
		}

		.pac-controls label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}

		#store-search {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 250px;
		}
		#store-search:focus {
			border-color: #4d90fe;
		}

		#edit_store-search {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 250px;
			z-index: 9999999;
		}

		#edit_store-search:focus {
			border-color: #4d90fe;
		}
		#title {
			color: #fff;
			background-color: #4d90fe;
			font-size: 25px;
			font-weight: 500;
			padding: 6px 12px;
		}
		#target {
			width: 250px;
		}
	</style>

@endsection
@section('content')
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">قائمة فروع {{$store->name_ar}}</h5>
			<div class="heading-elements">
				<ul class="icons-list">
					<li><a data-action="collapse"></a></li>
					<li><a data-action="reload"></a></li>
					<!-- <li><a data-action="close"></a></li> -->
				</ul>
			</div>
		</div>

		<!-- buttons -->
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-3">
					<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة فرع</span></button>
				</div>
				<div class="col-xs-3">
					<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الفروع : {{count($branches)}} </span> </button>
				</div>
			</div>
		</div>
		<!-- /buttons -->
		<table class="table datatable-basic">
			<thead>
			<tr>
				<!-- <th>الايقونة</th> -->
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>الهاتف</th>
				<th>العنوان</th>
				<th>التقييم</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
			</thead>
			<tbody>
			@foreach($branches as $branch)
				<tr>
				<!-- <td><img src="{{asset('img/store/icons/'.$branch->icon)}}" class="img-circle" alt=""></td> -->
					<td>{{$branch->name_ar}}</td>
					<td>{{$branch->name_en}}</td>
					<td>{{$branch->phone}}</td>
					<td>{{$branch->address}}</td>
					<td>{{($branch->num_rating > 0)? round(floatval($branch->rating / $branch->num_rating),1) : '0'}}</td>
					<td>{{Carbon\Carbon::parse($branch->created_at)->format('d/m/Y - H:i A')}}</td>
					<td>
						<ul class="icons-list">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-menu9"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<!-- edit button -->
									<li>
										<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal"
										   data-id="{{$branch->id}}"
										   data-storeid="{{$store->id}}"
										   data-namear="{{$branch->name_ar}}"
										   data-nameen="{{$branch->name_en}}"
										   data-phone="{{$branch->phone}}"
										   data-email="{{$branch->email}}"
										   data-address="{{$branch->address}}"
										   data-lat="{{$branch->lat}}"
										   data-long="{{$branch->lng}}"
										   data-website="{{$branch->website}}"
										   data-openfrom='{{$branch->open_from}}'							        
								           data-opento='{{$branch->open_to}}'
										>
											<i class="icon-pencil7"></i>تعديل
										</a>
									</li>
									<!-- delete button -->
									<form action="{{route('DeleteStore')}}" method="POST" id="DeleteCouponForm">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$branch->id}}">
										<li><button type="submit" id="delete" class="generalDelete reset" ><i class="icon-trash"></i>حذف</button></li>
									</form>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

		<!-- Add coupon Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">أضافة فرع جديد</h5>
					</div>
					<div class="modal-body">
						<div class="row">
							<form action="{{route('createBranch')}}" method="POST"  enctype="multipart/form-data" >
								{{csrf_field()}}
								<div class="row">
									<input type="hidden" name="store_id" value="{{$store->id}}">
									<div class="col-sm-4">
										<label>الهاتف</label>
									</div>
									<div class="col-sm-8">
										<input type="text" name="phone" class="form-control" placeholder="الهاتف " />
									</div>
									<div class="col-sm-4">
										<label>البريد الالكتروني</label>
									</div>
									<div class="col-sm-8">
										<input type="email" name="email" class="form-control" placeholder="البريد الالكتروني" />
									</div>
									<div class="col-sm-12">
										<input id="store-search" class="controls" type="text" placeholder="بحث">
										<div id="store_map" class="store_map" style="width: 100%;height:250px;"></div>
										<input type="hidden" id="lat" name="lat" class="form-control" >
										<input type="hidden" id="long" name="long" class="form-control" >
									</div>
									<div class="col-sm-4">
										<label>العنوان</label>
									</div>
									<div class="col-sm-8">
										<input type="text" name="address" id="address" class="form-control" placeholder="العنوان" required/>
									</div>
									<div class="col-sm-4">
										<label>الموقع الالكتروني</label>
									</div>
									<div class="col-sm-8">
										<input type="url" name="website" class="form-control" placeholder="http://www.example.com" />
									</div>
		                            <div class="col-sm-4">
				        				<label>مواعيد العمل من</label>
				        		    </div>
				        			<div class="col-sm-8">
				        				<input type="time" name="open_from" class="form-control" placeholder="8:00 AM" />
								    </div>						    	
		                            <div class="col-sm-4">
				        				<label>مواعيد العمل الي</label>
				        		    </div>
				        			<div class="col-sm-8">
				        				<input type="time" name="open_to" class="form-control" placeholder="11:00 PM" />
								    </div>
									<div class="col-sm-4">
										<label>قوائم الطعام </label>
									</div>
									<div class="col-sm-8">
										<input type="file" name="menus[]" multiple/>
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
		<!-- Add coupon Modal -->

		<!-- Edit coupon Modal -->
		<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"> تعديل بيانات الفرع </h5>
					</div>
					<div class="modal-body">
						<form action="{{route('updateBranch')}}" method="POST"  enctype="multipart/form-data" >
							{{csrf_field()}}
							<input type="hidden" name="id" value="">
							<input type="hidden" name="edit_store_id" value="">
							<div class="row">
								<div class="col-sm-12">
									<div class="col-sm-4">
										<label>الهاتف</label>
									</div>
									<div class="col-sm-8">
										<input type="text" name="edit_phone" class="form-control" placeholder="الهاتف " />
									</div>
									<div class="col-sm-4">
										<label>البريد الالكتروني</label>
									</div>
									<div class="col-sm-8">
										<input type="email" name="edit_email" class="form-control" placeholder="البريد الالكتروني" />
									</div>
									<div class="col-sm-12 store_map">
										<input id="edit_store-search" class="controls edit_store-search" type="text" placeholder="بحث">
										<div id="edit_store_map" class="edit_store_map" style="width: 100%;height:250px;"></div>
										<input type="hidden" id="edit_lat" name="edit_lat" class="form-control" >
										<input type="hidden" id="edit_long" name="edit_long" class="form-control" >
									</div>
									<div class="col-sm-4">
										<label>العنوان</label>
									</div>
									<div class="col-sm-8">
										<input type="text" name="edit_address" id="edit_address" class="form-control" placeholder="العنوان" required/>
									</div>
									<div class="col-sm-4">
										<label>الموقع الالكتروني</label>
									</div>
									<div class="col-sm-8">
										<input type="url" name="edit_website" class="form-control" placeholder="http://www.example.com" />
									</div>
		                            <div class="col-sm-4">
				        				<label>مواعيد العمل من</label>
				        		    </div>
				        			<div class="col-sm-8">
				        				<input type="time" name="edit_open_from" class="form-control" placeholder="8:00 AM" />
								    </div>						    	
		                            <div class="col-sm-4">
				        				<label>مواعيد العمل الي</label>
				        		    </div>
				        			<div class="col-sm-8">
				        				<input type="time" name="edit_open_to" class="form-control" placeholder="11:00 PM" />
								    </div>
									<div class="col-sm-4">
										<label>قوائم الطعام </label>
									</div>
									<div class="col-sm-8">
										<input type="file" name="edit_menus[]" multiple/>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" style="margin-top: 10px">
									<button type="submit" class="btn btn-primary" >حفظ التعديلات</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /Edit user Modal -->
	</div>


	<!-- javascript -->
@section('script')
	<script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection

<?php $google_places_key = setting('google_places_key');?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{$google_places_key}}&libraries=places&callback=initMap&language=ar"></script>
<script type="text/javascript">
	// function initMap(){
	var map; var marker;
	var myLatlng  = new google.maps.LatLng(23.8859, 45.0792);
	var geocoder  = new google.maps.Geocoder();
	var mapOptions = {
		zoom: 6,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	map = new google.maps.Map(document.getElementById("store_map"), mapOptions);
	marker = new google.maps.Marker({
		map: map,
		position: myLatlng,
		draggable: true
	});

	/*start search box*/
	// Create the search box and link it to the UI element.
	var input = document.getElementById('store-search');
	var searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});
	searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();
		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function(place) {
			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}
			marker.setPosition(place.geometry.location);
			$('#address').val(place.formatted_address);
			$('#lat').val(place.geometry.location.lat());
			$('#long').val(place.geometry.location.lng());
			if(place.geometry.viewport) {
				bounds.union(place.geometry.viewport);
			}else {
				bounds.extend(place.geometry.location);
			}
		});
		map.fitBounds(bounds);
	});
	/*end search box*/
	google.maps.event.addListener(marker, 'dragend', function() {
		geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$('#address').val(results[0].formatted_address);
					$('#lat').val(marker.getPosition().lat());
					$('#long').val(marker.getPosition().lng());
				}
			}
		});
	});

	// }
	// google.maps.event.addDomListener(window, 'load', initMap);

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id               = $(this).data('id')
		var store_id         = $(this).data('storeid')
		var name_ar          = $(this).data('namear')
		var name_en          = $(this).data('nameen')
		var phone            = $(this).data('phone')
		var email            = $(this).data('email')
		var icon             = $(this).data('icon')
		var address          = $(this).data('address')
		var lat              = $(this).data('lat')
		var lng              = $(this).data('long')
		var website          = $(this).data('website')
		var open_from        = $(this).data('openfrom')
		var open_to          = $(this).data('opento')

		//set values in modal inputs
		$("input[name='id']")           .val(id)
		$("input[name='edit_store_id']") .val(store_id)
		$("input[name='edit_name_ar']") .val(name_ar)
		$("input[name='edit_name_en']") .val(name_en)
		$("input[name='edit_phone']")   .val(phone)
		$("input[name='edit_email']")   .val(email)
		$("input[name='edit_address']") .val(address)
		$("input[name='edit_lat']")     .val(lat)
		$("input[name='edit_long']")    .val(lng)
		$("input[name='edit_website']") .val(website)
		$("input[name='edit_open_from']")  .val(open_from)
		$("input[name='edit_open_to']")  .val(open_to)
		var map; var marker;
		var myLatlng  = new google.maps.LatLng(lat, lng);
		var geocoder  = new google.maps.Geocoder();
		var mapOptions = {
			zoom: 15,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map(document.getElementById("edit_store_map"), mapOptions);
		marker = new google.maps.Marker({
			map: map,
			position: myLatlng,
			draggable: true
		});
		/*start search box*/
		// Create the search box and link it to the UI element.

		var input = document.getElementById('edit_store-search');
		if(input.val == null ){
			$(".store_map").prepend('<input id="edit_store-search" class="controls" type="text" placeholder="بحث">');
			$('.store_map input').first().remove();
			input = document.getElementById('edit_store-search');
		}
		var searchBox = new google.maps.places.SearchBox(input);
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		map.addListener('bounds_changed', function() {
			searchBox.setBounds(map.getBounds());
		});
		searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();
			// For each place, get the icon, name and location.
			var bounds = new google.maps.LatLngBounds();
			places.forEach(function(place) {
				if (!place.geometry) {
					console.log("Returned place contains no geometry");
					return;
				}
				marker.setPosition(place.geometry.location);
				$('#edit_address').val(place.formatted_address);
				$('#edit_lat').val(place.geometry.location.lat());
				$('#edit_long').val(place.geometry.location.lng());
				if(place.geometry.viewport) {
					bounds.union(place.geometry.viewport);
				}else {
					bounds.extend(place.geometry.location);
				}
			});
			map.fitBounds(bounds);
		});
		/*end search box*/
		google.maps.event.addListener(marker, 'dragend', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#edit_address').val(results[0].formatted_address);
						$('#edit_lat').val(marker.getPosition().lat());
						$('#edit_long').val(marker.getPosition().lng());
					}
				}
			});
		});
		// google.maps.event.addDomListener(window, 'load', initMap);		
		$('.edit_store-search').hide();

	});

	$('.generalDelete').on('click',function(e){
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
<script type="text/javascript">
	function ChooseFile(){$("input[name='edit_icon']").click()}
	function addChooseFile(){$("input[name='icon']").click()}
</script>
@endsection