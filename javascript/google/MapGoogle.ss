

<script src="http://maps.google.com/maps/api/js?v=3.2&sensor=false&amp;hl='. $this->lang.'" type="text/javascript"></script>
<% if UseClusterer %>
  <script src="$ClusterLibraryPath" type="text/javascript"></script>
 <% end_if %>
    

Main JS below

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


    	console.log('end of js');
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
                        map.setCenter(point, $Zoom);
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

    addAllMarkers();


</script>

 <div id="$GoogleMapID">
<% if ShowInlineMapDivStyle %>
  style="width:{$Width}px;{$Height}px;"';
<% end_if %>

<% if AdditionalCssClasses %>
   class="$AdditionalCssClasses";
<% end_if %>
</div>


<script type="text/javascript">
function load() {
console.log('mapping service load');
if (GBrowserIsCompatible()) {
    map = new google.maps.Map(document.getElementById("$GoogleMapID"));
    geocoder = new google.maps.Geocoder();

    <% if JsonMapStyles %>
      var mappableStyles=$JsonMapStyles;
      map.setOptions({styles: mappableStyles});
    <% end_if %>



    <% if EnableAutomaticCenterZoom %>
      map.setCenter(new google.maps.LatLng($LatLngCentre),$Zoom);
      var bds = new google.maps.LatLngBounds(new google.maps.LatLng($MinLat,$MinLng),
                new google.maps.LatLng($MaxLat,$MaxLng));
      map.setZoom(map.fitBounds(bds));
    <% else %>
      map.setCenter(new google.maps.LatLng($LatLngCentre));
      map.setZoom($Zoom);

    <% end_if %>


    map.setMapTypeId($MapType);
    google.maps.event.addListener(map,"click",function(overlay,latlng) { 
        if (latlng) { current_lat=latlng.lat();
            current_lng=latlng.lng(); 
        }
    });


    // add all the markers
    addAllMarkers();

    // add the lines
    //$this->content .= $this->contentLines;


}
}
</script>

End of main JS