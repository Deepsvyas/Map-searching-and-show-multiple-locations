<!DOCTYPE html>
<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
  <title>Google Maps Multiple Markers</title> 
  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
</head> 
<body>
  <div id="map" style="width: 500px; height: 400px;"></div>
  
  <script>
    // Define your locations: HTML content for the info window, latitude, longitude
    var locations = [
      ['<h4>Indore India</h4>', <?php echo get_lat_long('Indore India') ?>],
      ['<h4>Delhi India</h4>', <?php echo get_lat_long('Delhi India') ?>],
      ['<h4>Bombay India</h4>', <?php echo get_lat_long('Bombay India') ?>],
      ['<h4>Noida India</h4>', <?php echo get_lat_long('Noida India') ?>],
      ['<h4>Kolkata India</h4>', <?php echo get_lat_long('Kolkata India') ?>]
    ];
    
    // Setup the different icons and shadows
    var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';
    
    var icons = [
      iconURLPrefix + 'red-dot.png',
      iconURLPrefix + 'green-dot.png',
      iconURLPrefix + 'blue-dot.png',
      iconURLPrefix + 'orange-dot.png',
      iconURLPrefix + 'purple-dot.png',
      iconURLPrefix + 'pink-dot.png',      
      iconURLPrefix + 'yellow-dot.png'
    ]
    var iconsLength = icons.length;

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(-37.92, 151.25),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
      panControl: false,
      zoomControlOptions: {
         position: google.maps.ControlPosition.LEFT_BOTTOM
      }
    });

    var infowindow = new google.maps.InfoWindow({
      maxWidth: 160
    });

    var markers = new Array();
    
    var iconCounter = 0;
    
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: icons[iconCounter]
      });

      markers.push(marker);

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      
      iconCounter++;
      // We only have a limited number of possible icon colors, so we may have to restart the counter
      if(iconCounter >= iconsLength) {
      	iconCounter = 0;
      }
    }

    function autoCenter() {
      //  Create a new viewpoint bound
      var bounds = new google.maps.LatLngBounds();
      //  Go through each...
      for (var i = 0; i < markers.length; i++) {  
				bounds.extend(markers[i].position);
      }
      //  Fit these bounds to the map
      map.fitBounds(bounds);
    }
    autoCenter();
  </script> 


<?php 
    // Get lat long from google
    echo $latlong    =   get_lat_long('indore, India'); // create a function with the name   


	// function to get  the address
	function get_lat_long($address){

	    $address = str_replace(" ", "+", $address);

	    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
	    $json = json_decode($json);

	    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
	    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	    return $lat.','.$long;
	}
 ?>
</body>
</html>
