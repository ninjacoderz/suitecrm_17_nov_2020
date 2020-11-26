
<?php header('Access-Control-Allow-Origin: *');
?>
<div id="google_map"> 
<?php
// logic old - ajax get address by field address 
$lat = $_GET['lat'];
$lng = $_GET['lng'];
if(!isset($lat) && !isset($lng)){
  echo 'Could you check address site detail and save before please?';die();
}
?>  
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&callback=initMap" async defer></script>
  <style>
    #map {
      height: 220px;
      width: 220px;
      border-radius: 5px;
      background-color: #ffffff;
      border: 1px solid #808080;
      padding: 3px;
      margin-bottom:5px;
    }
  </style>
  <div id="map"></div>
  <script>
      var map;
      function initMap() {
          map = new google.maps.Map(document.getElementById('map'), {
          center: {<?php echo "lat: ".$lat .", lng: " .$lng; ?>},
          zoom:20,
          mapTypeId: 'satellite',
          tilt: 45,
          disableDefaultUI: true,     
      });
      map.addListener('tilesloaded', function(e) {
          $('.dismissButton').click();
          });
      }
        // let allScripts = document.getElementsByTagName( 'script' );
        [].filter.call(
          document.getElementsByTagName('script'), 
          ( scpt ) => scpt.src.indexOf( 'maps.googleapis.com/maps/api/js' ) >= 0
        )[ 0 ].remove();
        window.google = {};
  </script>
 
</div>