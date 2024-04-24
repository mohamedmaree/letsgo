@extends('layouts.app')

@section('content')

            <div id="map"></div>
            <!-- jQuery CDN -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script>
                var map;
                function initMap() { // Google Map Initialization... 
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 10,
                        center: new google.maps.LatLng("{{$lat}}", "{{$long}}"),
                        mapTypeId: 'terrain'
                    });
                }
            </script>
            <script async defer src="https://maps.googleapis.com/maps/api/js?key={{$googlemapkey}}&callback=initMap">
            </script>
@endsection
