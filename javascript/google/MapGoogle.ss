<pre>
DOWNLOADJS: $DownloadJS
</pre>

<% if DownloadJS %>
*** DOWNLOADING JS ****
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;hl=$Lang" type="text/javascript"></script>
<% if UseClusterer %>
  <script src="$ClusterLibraryPath" type="text/javascript"></script>
 <% end_if %>

 <% else %>
 no need for JS
<% end_if %>    
<script type="text/javascript">
    var map;
    console.log('Map template JS loaded');
    var gmarkers = [];
    var gicons = [];
    var clusterer = null;
    var current_lat = 0;
    var current_lng = 0;
    var layer_wikipedia = null;
    var layer_panoramio = null;
    var trafficInfo = null;
    var directions = null;
    var geocoder = null;
    var infoWindow = new google.maps.InfoWindow({ content: 'test', maxWidth: 300 });





    function createMarker(lat,lng,html,category,icon) {
  		var marker = new google.maps.Marker();
    	marker.setPosition(new google.maps.LatLng(lat,lng));



    	marker.mycategory = category;

	    if (icon != '') {
		    var image = new google.maps.MarkerImage(icon);
	    	marker.setIcon(image);
	    }

	    <% if UseClusterer %>
	    <% else %>
	   	marker.setMap(map);
	    <% end_if %>


	    html = '<div style="float:left;text-align:left;width:{$InfoWidth}px;">Something</div>';
	    google.maps.event.addListener(marker,"click",function() {
		    <% if EnableWindowZoom %>
		      map.setCenter(new google.maps.LatLng(lat,lng),$InfoWindowZoom);
		    <% end_if %>

	   		infoWindow.setContent(html);
	    	infoWindow.open(map, this);
	     });

        gmarkers.push(marker);

        <% if DefaultHiderMarker %>
            marker.hide();
        <% end_if %>
    }



    // JS public function to get current Lat & Lng
    function getCurrentLat() {
        return current_lat;
    }

    function getCurrentLng() {
        return current_lng;
    }



    // JS public function to center the gmaps dynamically
    function showAddress(address) {
        if (geocoder) {
            geocoder.getLatLng(
                address,
                function(point) {
                    if (!point) { alert(address + " not found"); }
                    else {
                        map.setCenter(point);
                        map.setZoom($Zoom);
                    }
            });
        }
    }

    function addAllMarkers() {
        var markers = $MapMarkers;
        for (var i=0; i<markers.length;i++) {
            var marker = markers[i];
            createMarker(marker.latitude, marker.longitude, marker.html, marker.category, marker.icon);
        }

    }



</script>

 <div id="$GoogleMapID" style="height:500px;width:500px">

<% if ShowInlineMapDivStyle %>
  style="width:{$Width}px;{$Height}px;"';
<% end_if %>

<% if AdditionalCssClasses %>
   class="$AdditionalCssClasses";
<% end_if %>
</div>


<script type="text/javascript">
function loadmaps() {
console.log('mapping service load');
    map = new google.maps.Map(document.getElementById("$GoogleMapID"));
    map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
    geocoder = new google.maps.Geocoder();

    <% if JsonMapStyles %>
      var mappableStyles=$JsonMapStyles;
      map.setOptions({styles: mappableStyles});
    <% end_if %>



    <% if EnableAutomaticCenterZoom %>
      map.setCenter(new google.maps.LatLng($LatLngCentre));
      var bds = new google.maps.LatLngBounds(new google.maps.LatLng($MinLat,$MinLng),
                new google.maps.LatLng($MaxLat,$MaxLng));
      map.setZoom(map.fitBounds(bds));
    <% else %>
      map.setCenter(new google.maps.LatLng($LatLngCentre));
      map.setZoom($Zoom);

    <% end_if %>


    map.setMapTypeId($MapType);
    google.maps.event.addListener(map,"click",function(overlay,latlng) { 
        if (latlng) { 
            current_lat=latlng.lat();
            current_lng=latlng.lng(); 
        }
    });


    addAllMarkers();

    /*
    Add lines here 
    */

    
}



    <% if UseClusterer %>
        var markerCluster = new MarkerClusterer(map, gmarkers,{gridSize: $GridSize, maxZoom: $MaxZoom});
    <% end_if %>


function loadedGoogleMapsAPI() {
    console.log('callback from google maps');
    loadmaps();
}

<% if DelayLoadMapFunction %>
console.log('delay map loading');
<% else %>
console.log("adding google maps load callback");
google.maps.event.addDomListener(window, 'load', loadedGoogleMapsAPI);
<% end_if %>
</script>