/* mapping of google_map_N to an array of markers */
<% if DownloadJS %>
var infoWindows = [];
var gmarkers = [];
var mapLayers = [];
var mapLines = [];
<% end_if %>
registerMap('$GoogleMapID', $LatLngCentre, $Zoom, $MinLat,$MinLng,$MaxLat,$MaxLng, $MapType,
$MapMarkers,$Lines,$KmlFiles, $JsonMapStyles, $EnableAutomaticCenterZoom, $UseClusterer,
$AllowFullScreen);
