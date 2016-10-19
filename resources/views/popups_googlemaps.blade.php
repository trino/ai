<style>
    #map {
        height: 500px;
        border-color: red;
    }
</style>
<script>
    var map, infowindow, service, markers = new Array();

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: <?= $latitude; ?>, lng: <?= $longitude; ?>},
            zoom: 15
        });

        infowindow = new google.maps.InfoWindow();
        service = new google.maps.places.PlacesService(map);
    }

    function addmarker(Name, Latitude, Longitude){
        var myLatlng = new google.maps.LatLng(parseFloat(Latitude),parseFloat(Longitude));
        var marker = new google.maps.Marker({
            map: map,
            position: myLatlng
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(Name);
            infowindow.open(map, this);
        });
    }

    function addmarker2(Name, Latitude, Longitude){
        if(isUndefined(Name)){
            for(var id = 0; id < markers.length; id++){
                addmarker(markers[id]["Name"], markers[id]["Latitude"], markers[id]["Longitude"]);
            }
        } else {
            for(var id = 0; id < markers.length; id++){
                if(markers[id]["Latitude"] == Latitude && markers[id]["Longitude"] == Longitude){
                    markers[id]["Name"] += "<BR>" + Name;
                    return id;
                }
            }
            markers.push({Name: Name, Latitude: Latitude, Longitude: Longitude});
        }
    }
</script>
<div id="map"></div>
@if(!isset($includeapi))
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWSUc8EbZYVKF37jWVCb3lpBQwWqXUZw8&signed_in=true&libraries=places&callback=initMap" async defer></script>
@else
    <SCRIPT>
        $(window).load(function() {
           initMap();
        });
    </SCRIPT>
@endif

