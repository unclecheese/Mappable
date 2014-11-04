<% if DownloadJS %>
<% if DelayLoadMapFunction %>
console.log('delay map loading');
<% else %>
<script src="//maps.google.com/maps/api/js?sensor=false&amp;hl=$Lang" type="text/javascript"></script>
<script type="text/javascript" src="/mappable/javascript/google/maputil.js"></script>

<script type="text/javascript">google.maps.event.addDomListener(window, 'load', loadedGoogleMapsAPI);</script>
<% end_if %>



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
// mapping of google_map_N to an array of markers

<% if DownloadJS %>
 var infoWindows = [];
 var gmarkers = [];
 var mapLayers = [];
 var mapLines = [];
<% end_if %>
    registerMap('$GoogleMapID', $LatLngCentre, $Zoom, $MinLat,$MinLng,$MaxLat,$MaxLng, $MapType, $MapMarkers, $Lines,$KmlFiles, $JsonMapStyles, $EnableAutomaticCenterZoom, $UseClusterer);
</script>

 <div id="$GoogleMapID" <% if ShowInlineMapDivStyle %>style="width:{$Width}; height: {$Height};"<% end_if %><% if AdditionalCssClasses %> class="$AdditionalCssClasses"<% end_if %>>
</div>

