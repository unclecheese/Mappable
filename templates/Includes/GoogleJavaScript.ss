<% if DownloadJS %>
<% if DelayLoadMapFunction %>
<% else %>
<script type="text/javascript">
function registerShortcodeMap(options) {
	newMap = [];
	newMap.latitude = options.latitude;
	newMap.longitude = options.longitude;
	newMap.zoom = options.zoom;
	newMap.maptype = options.maptype;
	newMap.allowfullscreen = options.allowfullscreen;
	newMap.caption = options.caption;
	newMap.domid = options.domid;
	shortcodeMaps.push(newMap);
}


function registerStreetView(options) {
	newView = [];
	newView.latitude = options.latitude;
	newView.longitude = options.longitude;
	newView.zoom = options.zoom;
	newView.pitch = options.pitch;
	newView.heading = options.heading;
	newView.caption = options.caption;
	newView.domid = options.domid;
	shortcodeStreetview.push(newView);
}


function registerMap(options) {
	var newMap = [];
	newMap.googleMapID = options.domid;
	newMap.zoom = options.zoom;
	newMap.centreCoordinates = options.centre;

	newMap.markers = options.mapmarkers;
	newMap.mapType = options.maptype;
	newMap.lines = options.lines;
	newMap.kmlFiles = options.kmlfiles;
	newMap.jsonMapStyles = options.mapstyles;
	newMap.enableAutomaticCenterZoom = options.enableautocentrezoom;
	newMap.useClusterer = options.useclusterer;
	newMap.clustererGridSize = options.clusterergridsize;
	newMap.clustererMaxZoom = options.clusterermaxzoom;
	newMap.allowFullScreen = options.allowfullscreen;
	var googleMapID = options.domid;
	mappableMaps[googleMapID] = newMap;

	// increment map counter
	mappableMapCtr++;

	// initialise gmarkers array for this map
	gmarkers[googleMapID] = [];

	mapLayers[googleMapID] = options.kmlfiles;
	mapLines[googleMapID] = options.lines;
}


</script>
<% if $UseCompressedAssets %>
<% require javascript("mappable/javascript/google/mappablegoogle.min.js") %>
<% else %>
<script type="text/javascript">
var mappableMapCtr = 0;
var mappableMaps = [];
var shortcodeMaps = [];
var shortcodeStreetview = [];
</script>
<% require javascript("mappable/javascript/google/FullScreenControl.js") %>
<% require javascript("mappable/javascript/google/markerclusterer.js") %>
<% require javascript("mappable/javascript/google/maputil.js") %>
<% end_if %>
<% end_if %>
<!-- end of common js -->
<% end_if %>
