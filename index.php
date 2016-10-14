<!DOCTYPE html>
<html>
  <head>
    <title>Port Town Restaurant Finder</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/stylesform.css">
  </head>

<?php 
// Set "Street & Co."" as the initial search on loading.
// Change to the text input value if the form has been submitted.
// Set "e.g. Taqueria" as the variable for the placeholder.  
  if (isset($_POST['submitButton'])){
    $searchTerm = $_POST["searchBox"];
    $placeTerm = $searchTerm;
  } else {
    $searchTerm = "Street & Co."; 
    $placeTerm = 'e.g. Taqueria';
  }
// Translate the php variable from the text input to a JavaScript variable.
  echo "<script>";
  echo "var search = " . json_encode($searchTerm) . ";";
  echo "</script>";
?>

  <body>
<!--Creat a top banner for the question and search form -->  
    <div id="banner">
      <p id="question">Where do you want to eat?</p>
      <form action="" method="post">
        <input id="search" type="text" name="searchBox" placeholder="<?php echo $placeTerm ?>">
        <input id="submit" type="submit" name="submitButton" style="display: none;">
      </form>
    </div>
<!-- This div holds the map returned by the Google Maps JavaScript API -->
    <div id="map"></div>

    <script>
      var map; // This holds the map object.
      var infowindow; // This holds the info displayed when pin is clicked.



// Query the Google Maps API to get a map with center in the Old Port.
      function initMap() {
        var oldPort = {lat: 43.6548, lng: -70.255};

        map = new google.maps.Map(document.getElementById('map'), {
          center: oldPort,
          zoom: 16
        });

        infowindow = new google.maps.InfoWindow();

// Query the Google Maps Places API using term from the user.
// Limit the search to restaurants in the second level of pricing.
// Limit the search to within 10000 meters of the map's center.       
        var service = new google.maps.places.PlacesService(map);
          service.textSearch({
            query: search,
            type: ['restaurant'],
            minPriceLevel: 2,
            location: oldPort,
            radius: 10000
          }, callback);
      }

// Loop through the results of the query.
// Place a marker for each result.
// Reset the map center on the location of the first result.
      function callback(results, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
            createFirstMarker(results[0]);
            map.setCenter(results[0].geometry.location); 
          } 
        }
      }
// Create a marker (pin) to drop on the highest ranked search result.
      function createFirstMarker(topHit) {
        var marker = new google.maps.Marker({
          map: map,
          position: topHit.geometry.location,
          title: topHit.name,
          animation: google.maps.Animation.DROP
        });

// Show the address of the restaurant when user clicks on the pin.
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent(topHit.formatted_address);
          infowindow.open(map, this);
        });
      }        
    
// Create a marker (custom) with the reataurant name exposed on hover.
      function createMarker(place) {
// Use this custom icon instead of the pin as a marker.
        var icons = 'images/restaurant-icon-smallest.png';

        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location,
          title: place.name,
          icon: icons
        });
// Show the address of the restaurant when user clicks on custom marker.
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent(place.formatted_address);
          infowindow.open(map, this);
        });
      }
    </script>
<!-- Google Maps JavaScript API with key and places library. -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCupSW7kGdQ2uOEB1OFNpRtQkGxTHQaosM&libraries=places&callback=initMap" async defer></script>
  </body>
</html>