@extends('dashboard.layout.master')
    @section('title')
    اشعارات الخريطة
    @endsection
@section('content')
<div class="panel panel-flat first">
    <div class="panel-heading">
            <div class="row">

                <div class="col-lg-12">
                        <div class="card visitors-card">
                            <div class="card-content">
                              <h2 style="margin-right: 38%;">اشعارات الخريطة</h2>
                                <input id="place-search" style="z-index: 0;position: absolute;left: 250px;top: 0px;" class="controls" type="text" placeholder="بحث ">
                                <div class="map" id="map" style="with:90%;height:500px;"> 
                                </div>
                            </div>
                        </div>
                </div>

	            <div class="col-lg-12">
			    	<form action="{{route('sendMapNotifications')}}" method="POST" enctype="multipart/form-data">
			    		{{csrf_field()}}
			            <div class="col-sm-12">
			                <input type="hidden" placeholder="العنوان" name="address" id="address" class="form-control"/>
			                <input type="hidden" name="lat" id="lat" value="{{old('lat')}}">
			                <input type="hidden" name="lng" id="lng" value="{{old('lng')}}">
			            </div>

			    		<div class="col-sm-12">
                            <input type="text" name="notification_title" class="form-control" placeholder="عنوان الاشعار" required />
			    		</div>
			    		<div class="col-sm-12">
			    			<textarea rows="15" name="notification_message" class="form-control" placeholder="نص الاشعار " required></textarea>
			    		</div>
			    		
			    		<div class="col-sm-12">
	                		<input type="number" class="form-control" placeholder="أقصي مسافة" name="max_distance"  value="{{old('max_distance')}}" min="1" required>
			    		</div>
			    		<div class="col-sm-12">
	                		<select name="type" class="form-control">
	                			<option value="all">الكل</option>
	                			<option value="clients">العملاء</option>
	                			<option value="captains">القادة</option>
	                		</select>
			    		</div>
				        <div class="col-sm-12">
				        	<label class="radio-container"> اشعار 
                                <input type="radio" name="notify_type" value="notification" >
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio-container"> رسالة SMS 
                                <input type="radio" name="notify_type" value="sms" >
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio-container"> رسالة Email 
                                <input type="radio" name="notify_type" value="email" >
                                <span class="checkmark"></span>
                            </label>
				        </div>
                        
				        <div class="col-sm-12">
					      	<button type="submit" class="btn btn-primary">ارسال</button>
				        </div>
			    	</form>

	            </div>   


            </div>   
    </div>  
</div>  

<?php $google_places_key = setting('google_places_key');?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{$google_places_key}}&libraries=places&language=ar"></script>
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

        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: true
        });

/*start search box*/
        // Create the search box and link it to the UI element.
        var input = document.getElementById('place-search');
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
            $('#lng').val(place.geometry.location.lng());
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
                        $('#lng').val(marker.getPosition().lng());
                    }
                }
            });
        });
    
    // }
    // google.maps.event.addDomListener(window, 'load', initMap);


</script>
@endsection
