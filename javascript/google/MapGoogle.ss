<% if DownloadJS %>
<% if DelayLoadMapFunction %>
console.log('delay map loading');
<% else %>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;hl=$Lang" type="text/javascript"></script>
<script type="text/javascript" src="/mappable/javascript/google/maputil.js"></script>

<script type="text/javascript">
console.log("adding google maps load callback");


google.maps.event.addDomListener(window, 'load', loadedGoogleMapsAPI);
</script>
<% end_if %>


<script type="text/javascript" src="/mappable/javascript/stacktrace-min-0.4.js"></script>

<script type="text/javascript">
// map details are stored here and used to invoke maps in the loadedGoogleMapsAPI function
var mappableMaps = [];
</script>
<!-- end of common js --> 

<% end_if %>   
<% if UseClusterer %>
  <script src="$ClustererLibraryPath" type="text/javascript"></script>


 <% end_if %>

 

<script type="text/javascript">
/*
    var map;
    console.log('Map template JS loading: $GoogleMapID');
    
    var gicons = [];
    var fluster = null;
    var current_lat = 0;
    var current_lng = 0;
    var layer_wikipedia = null;
    var layer_panoramio = null;
    var trafficInfo = null;
    var directions = null;
    var geocoder = null;
    var infoWindow = new google.maps.InfoWindow({ content: 'test', maxWidth: 400 });
*/

// mapping of google_map_N to an array of markers

<% if DownloadJS %>
 var infoWindows = [];
 var gmarkers = [];
 var mapLayers = [];
 var mapLines = [];
<% end_if %>
    registerMap('$GoogleMapID', $LatLngCentre, $Zoom, $MinLat,$MinLng,$MaxLat,$MaxLng, $MapType, $MapMarkers, $Lines,$KmlFiles, $JsonMapStyles, $EnableAutomaticCenterZoom, $UseClusterer);
</script>

 <div id="$GoogleMapID" <% if ShowInlineMapDivStyle %>style="width:{$Width}px;{$Height}px;"<% end_if %><% if AdditionalCssClasses %>class="$AdditionalCssClasses"<% end_if %>
>
</div>

