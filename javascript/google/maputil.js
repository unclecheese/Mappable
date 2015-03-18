/**
 * Counter used for uniquely identifying each Google map
 * @type {Number}
 */
var mappableMapCtr = 0;

/**
 * Create a google map pin from data provided
 * @param  {GoogleMap} map             Instance of a google map
 * @param  {Number} lat                Latitude of pin
 * @param  {Number} lng                Longitude of pin
 * @param  {String} html               HTML for information window
 * @param  {String} icon               URL of alternative map icon, or blank for default
 * @param  {boolean} useClusterer      Whether or not to use clusterer
 * @param  {boolean} enableWindowZoom  Whether or not to enable zoom on the rendered map
 * @param  {boolean} defaultHideMarker Whether or not to hide markers initially
 * @return {MapMarker}                 Google map pin object
 */
function createMarker(map, lat, lng, html, category, icon, useClusterer, enableWindowZoom,
	defaultHideMarker) {

	mapId = map.getDiv().getAttribute('id');

	var marker = new google.maps.Marker();
	marker.setPosition(new google.maps.LatLng(lat, lng));
	marker.mycategory = category;

	if (icon && icon !== '') {
		var image = new google.maps.MarkerImage(icon);
		marker.setIcon(image);
	}

	if (!useClusterer) {
		marker.setMap(map);
	}

	google.maps.event.addListener(marker, "click", function() {
		if (enableWindowZoom) {
			map.setCenter(new google.maps.LatLng(lat, lng), 12); // $InfoWindowZoom);
		}
		var infoWindow = infoWindows[mapId];
		infoWindow.setContent(html);
		infoWindow.open(map, this);
	});

	gmarkers[mapId].push(marker);

	if (defaultHideMarker) {
		marker.hide();
	}
	return marker;
}


/**
 * Get the current latitude
 * @return {float} Current latitude
 */
function getCurrentLat() {
	return current_lat;
}

/**
 * Get the current longitude
 * @return {float} The current longitude
 */
function getCurrentLng() {
	return current_lng;
}


/**
 * Convert JSON point data into google map markers
 * @param {GoogleMap} map             Google Map instance
 * @param {array} markers            point data loaded from JSON
 * @param {boolean} useClusterer      Whether or not to use the clusterer
 * @param {boolean} enableWindowZoom  Whether or not zoom is enabled
 * @param {boolean} defaultHideMarker Whether or not to hide markers
 * * @return array of Google map markers converted from the JSON data
 */
function addAllMarkers(map, markers, useClusterer, enableWindowZoom, defaultHideMarker) {
	var allmarkers = [];
	for (var i = 0; i < markers.length; i++) {
		var markerinfo = markers[i];
		var marker = createMarker(map, markerinfo.latitude, markerinfo.longitude, markerinfo.html,
			markerinfo.category, markerinfo.icon, useClusterer, enableWindowZoom,
			defaultHideMarker);
		allmarkers.push(marker);
	}
	return allmarkers;
}

/**
 * Add lines to a Google map
 * @param {googleMap} map   Google map instance
 * @param {array} lines Line data loaded from json, lat1,lon1 to lat2,lon2
 */
function addLines(map, lines) {
	for (i = 0; i < lines.length; i++) {
		var line = lines[i];
		var point1 = new google.maps.LatLng(line.lat1, line.lon1);
		var point2 = new google.maps.LatLng(line.lat2, line.lon2);
		var points = [point1, point2];
		var pl = new google.maps.Polyline({
			path: points,
			strokeColor: line.color,
			strokeWeight: 4,
			strokeOpacity: 0.8
		});
		pl.setMap(map);
	}
}

/**
 * Add one or more (max of 25) KML files to a Google map
 * @param {GoogleMap} map      A Google Map instance
 * @param {array} kmlFiles array of URLs for KML files
 */
function addKmlFiles(map, kmlFiles) {
	for (var i = 0; i < kmlFiles.length; i++) {
		var kmlFile = kmlFiles[i];
		var kmlLayer = new google.maps.KmlLayer(kmlFile, {
			suppressInfoWindows: true,
			map: map
		});

	}
}


function registerMap(googleMapID, centreCoordinates, zoom, minLat, minLng, maxLat, maxLng, mapType,
	markers, lines, kmlFiles, jsonMapStyles, enableAutomaticCenterZoom, useClusterer,
	allowFullScreen) {
	var newMap = [];
	newMap.googleMapID = googleMapID;
	newMap.zoom = zoom;
	newMap.centreCoordinates = centreCoordinates;
	newMap.minLat = minLat;
	newMap.minLng = minLng;
	newMap.maxLng = maxLng;
	newMap.maxLat = maxLat;
	newMap.markers = markers;
	newMap.googleMapID = googleMapID;
	newMap.mapType = mapType;
	newMap.lines = lines;
	newMap.kmlFiles = kmlFiles;
	newMap.jsonMapStyles = jsonMapStyles;
	newMap.enableAutomaticCenterZoom = enableAutomaticCenterZoom;
	newMap.useClusterer = useClusterer;
	newMap.allowFullScreen = allowFullScreen;
	mappableMaps[googleMapID] = newMap;

	// increment map counter
	mappableMapCtr++;

	// initialise gmarkers array for this map
	gmarkers[googleMapID] = [];
	var infoWindow = new google.maps.InfoWindow({
		content: 'test',
		maxWidth: 400
	});
	infoWindows[googleMapID] = infoWindow;

	mapLayers[googleMapID] = kmlFiles;
	mapLines[googleMapID] = lines;
}


/**
 * Callback function after the Google Maps API has been loaded - renders the maps along with
 * associated points of interest and layers
 */
function loadedGoogleMapsAPI() {
	for (var i = 1; i <= mappableMapCtr; i++) {
		var map_info = mappableMaps['google_map_' + i];
		var map = new google.maps.Map(document.getElementById(map_info.googleMapID));

		// initialise geocoder
		geocoder = new google.maps.Geocoder();

		// TODO
		// if (map_info.jsonMapStyles) {
		//		map.setOptions({styles: map_info.jsonMapStyles});
		//	};

		if (map_info.allowFullScreen) {
			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
				FullScreenControl(map, "Full Screen", "Original Size")
			);
		}
		if (map_info.enableAutomaticCenterZoom) {
			centre = map_info.centreCoordinates;
			map.setCenter(new google.maps.LatLng(centre.lat, centre.lng));

			var bds = new google.maps.LatLngBounds(new google.maps.LatLng(map_info.minLat, map_info.minLng),
				new google.maps.LatLng(map_info.maxLat, map_info.maxLng));
			map.fitBounds(bds);
			map.setZoom(map_info.zoom);
		} else {
			var centre = map_info.centreCoordinates;
			map.setCenter(new google.maps.LatLng(centre.lat, centre.lng));
			map.setZoom(map_info.zoom);
		}

		if (map_info.mapType) {
			map.setMapTypeId(map_info.mapType);
		} else {
			map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
		}

		var markers = addAllMarkers(map, map_info.markers, map_info.useClusterer,
			map_info.enableAutomaticCenterZoom, map_info.defaultHideMarker);

		if (map_info.useClusterer) {
			var mcOptions = {gridSize: 50, maxZoom: 17};
			var markerCluster = new MarkerClusterer(map,markers,mcOptions);
		}

		addLines(map, map_info.lines);
		addKmlFiles(map, map_info.kmlFiles);
	}
}
