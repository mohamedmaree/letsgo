<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>{{setting('site_title')}}</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: {{$lat}}, lng: {{$long}} },
          zoom: 11,
          disableDefaultUI: true,
          gestureHandling: 'none',
          zoomControl: false        
        });

        // Create an array of alphabetical characters used to label the markers.
        // var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            // label: labels[i % labels.length],
            icon: "{{url('img/dot.png')}}",
          });
        });

        // Add a marker clusterer to manage the markers.
        // var markerCluster = new MarkerClusterer(map, markers,
        //     {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            var clusterStyles = [
             {
                textColor: 'white',
                url: "{{url('img/marker3.png')}}",
                height: 40,
                width: 40
              },            
              {
                textColor: 'white',
                url: "{{url('img/marker4.png')}}",
                height: 60,
                width: 60
              },
             {
                textColor: 'white',
                url: "{{url('img/marker5.png')}}",
                height: 80,
                width: 80
              }
            ];

            var mcOptions = {
                gridSize: 50,
                styles: clusterStyles,
                maxZoom: 12,
                zoomOnClick:false,
                averageCenter:false,
                minimumClusterSize:parseInt("{{intval(setting('first_rush_hour'))}}"),
            };
            var markerclusterer = new MarkerClusterer(map, markers, mcOptions);     

            markerclusterer.setCalculator(function(markers, numStyles){
                //create an index for icon styles
                var index = 0,
                //Count the total number of markers in this cluster
                    count = markers.length,
                //Set total to loop through (starts at total number)
                    total = count;

                /**
                 * While we still have markers, divide by a set number and
                 * increase the index. Cluster moves up to a new style.
                 *
                 * The bigger the index, the more markers the cluster contains,
                 * so the bigger the cluster.
                 */
                while (total !== 0) {
                    //Create a new total by dividing by a set number
                    total = parseInt(total / parseInt("{{intval(setting('first_rush_hour'))}}") ,10);
                    //Increase the index and move up to the next style
                    index++;
                }

                /**
                 * Make sure we always return a valid index. E.g. If we only have
                 * 5 styles, but the index is 8, this will make sure we return
                 * 5. Returning an index of 8 wouldn't have a marker style.
                 */
                index = Math.min(index, numStyles);

                //Tell MarkerCluster this clusters details (and how to style it)
                    var text = '';
                    if(count >= parseInt("{{intval(setting('third_rush_hour'))}}") ){
                      text = "{{setting('third_rush_hour_percentage')}}";
                    }else if(count >= parseInt("{{intval(setting('second_rush_hour'))}}") ){
                      text = "{{setting('second_rush_hour_percentage')}}";
                    }else if(count >= parseInt("{{intval(setting('first_rush_hour'))}}") ){
                      text = "{{setting('first_rush_hour_percentage')}}";
                    } 
                return {
                    text: '1.'+text+' x',
                    index: index
                };
            });


      }


      var locations = [
        <?php foreach($orders as $order){?>
            { lat: <?=$order->start_lat;?>, lng: <?=$order->start_long;?> },
        <?php }?>
        ]

    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{$google_key}}&callback=initMap">
    </script>
  </body>
</html>