<% include GoogleJavaScript %>

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