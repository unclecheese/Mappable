/* mapping of google_map_N to an array of markers */
<% if DownloadJS %>
var infoWindows = [];
var gmarkers = [];
var mapLayers = [];
var mapLines = [];
<% end_if %>
var options = {
	centre: $LatLngCentre,
	zoom: $Zoom,
	maptype: '$MapType',
	domid: '$GoogleMapID',
	allowfullscreen: $AllowFullScreen,
	mapmarkers: $MapMarkers,
	lines: $Lines,
	kmlfiles: $KmlFiles,
	mapstyles: $JsonMapStyles,
	useclusterer: $UseClusterer,
	enableautocentrezoom: $EnableAutomaticCenterZoom
}

registerMap(options);
