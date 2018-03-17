<!DOCTYPE html>
<html>

<head>
  <title>Location Tracker</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
  <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
  <style>
  html,
  body,
  #map {
    height: 100%;
    width: 100%;
    padding: 0px;
    margin: 0px;
  }

</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="modalstyle.css">
</head>

<body>


  <div id="map" style="width: 100%; height: 100vh; padding:0px;
  margin:0px;"></div>



  <script>
    var mapOptions = {
      center: [14.193776, 121.160055],
      zoom: 20
    }
    var map = new L.map('map', mapOptions);

    var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

    L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
      maxZoom: 20,
      subdomains:['mt0','mt1','mt2','mt3']
    }).addTo(map);;



    $(document).ready(function() {
      loadMarkers();

    });

    var circ = new L.circle();
    var marker = new L.marker();
    var polylines = [];
    function focusCoords(latitude, longitude){
    map.panTo(new L.LatLng(latitude, longitude));
    map.setZoom(20);

    map.removeLayer(circ);
    map.removeLayer(marker);
    marker = L.marker([latitude, longitude]);
    marker.addTo(map);


    var  radius = 5; 
    circ = L.circle(new L.LatLng(latitude, longitude), radius, {color: '#f00', fillColor: '#00f'});
    circ.addTo(map);
  }

  function loadMarkers() {

    for (var i = 0, len = polylines.length; i < len; i++) {
      map.removeLayer(polylines[i]);
    }
    $.ajax({
      url: 'mapfunction.php',
      type: 'POST',
      cache: false,
      data: { action: "getAllMarkers" },
      dataType: 'json',
      success: function(result) {
        var size = result.length;


        if(size > 0){
          var pointA = new L.LatLng(result[0]["latitude"], result[0]["longitude"]);

          for(var i = 1; i < size; i++){
            var pointB = new L.LatLng(result[i]["latitude"], result[i]["longitude"]);
            var pointList = [pointA, pointB];

            var firstpolyline = new L.Polyline(pointList, {
              color: 'red',
              weight: 10,
              opacity: 0.5,
              smoothFactor: 1
            });
            firstpolyline.addTo(map);
            polylines.push(firstpolyline);


            pointA = pointB;
          }
          focusCoords(result[size - 1]["latitude"], result[size - 1]["longitude"]);


        }

      },
      error: function() {}
    });
  }


  setInterval(function(){

    loadMarkers();
  }, 5000);
</script>

</body>
</html>
