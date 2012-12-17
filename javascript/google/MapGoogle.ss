MAPPING TEMPLATE 4:

<script src="http://maps.google.com/maps/api/js?v=3.2&sensor=false&amp;hl='. $this->lang.'" type="text/javascript"></script>
<% if UseClusterer %>
  <script src="$ClusterLibraryPath" type="text/javascript"></script>
 <% end_if %>
    

Main JS below

<script type="text/javascript">
    alert('wigglety woo');
    var map;
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

    alert('end of js');


</script>

End of main JS