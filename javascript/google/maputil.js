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

	// these will be altered by adding markers
	map.minLat = 1000000;
	map.minLng = 1000000;
	map.maxLat = -1000000;
	map.maxLng = -1000000;


	var allmarkers = [];
	for (var i = 0; i < markers.length; i++) {
		var markerinfo = markers[i];
		var marker = createMarker(map, markerinfo.latitude, markerinfo.longitude, markerinfo.html,
			markerinfo.category, markerinfo.icon, useClusterer, enableWindowZoom,
			defaultHideMarker);

		var latitude = parseFloat(markerinfo.latitude);
		var longitude = parseFloat(markerinfo.longitude);

		// update lat,lon min to lat,lon max
		if (latitude < map.minLat) {
			map.minLat = latitude;

		}
		if (latitude > map.maxLat) {
			map.maxLat = latitude;
		}

		if (longitude > map.maxLng) {
			map.maxLng = longitude;

		}

		if (longitude < map.minLng) {
			map.minLng = longitude;
		}

		allmarkers.push(marker);
	}


	var centreCoordinates = [];
	centreCoordinates.lat = (parseFloat(map.minLat)+parseFloat(map.maxLat))/2;
	centreCoordinates.lng = (parseFloat(map.minLng)+parseFloat(map.maxLng))/2;
	map.centreCoordinates = centreCoordinates;
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


/**
 * Convert a map type name (road,satellite,hybrid,terrain) to Google map types
 * @param  String mapTypeName  		generic name of the map type
 * @return google.maps.MapTypeId 	map type in Google format
 */
function convertMapType(mapTypeName) {
	var result = google.maps.MapTypeId.ROADMAP;
	switch (mapTypeName) {
		case 'aerial':
			result = google.maps.MapTypeId.SATELLITE;
			break;
		case 'hybrid':
			result = google.maps.MapTypeId.HYBRID;
			break;
		case 'terrain':
			result = google.maps.MapTypeId.TERRAIN;
			break;
	}
	return result;
}


/**
 * Register a short code generated map, namely storing parameters for later rendering
 * once google maps API loaded
 * @param  array options lat,lon,zoom,type
 */
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

/**
 * Register a short code generated streetview, namely storing parameters for later rendering
 * once google maps API loaded
 * @param  array options lat,lon,zoom,pitch,heading,caption,domid
 */
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


//function registerMap(googleMapID, centreCoordinates, zoom, mapType,
//	markers, lines, kmlFiles, jsonMapStyles, enableAutomaticCenterZoom, useClusterer,
//	allowFullScreen) {

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


/**
 * After the Google Maps API has been loaded init the relevant Google maps
 */
function loadShortCodeStreetView() {
	for (var i = 0; i < shortcodeStreetview.length; i++) {
		view = shortcodeStreetview[i];
		var location = new google.maps.LatLng(view.latitude, view.longitude);

		var mapOptions = {
		  center: location,
		  zoom: view.zoom
		};

		var map = new google.maps.Map(
    		document.getElementById(view.domid), mapOptions);

		  var panoramaOptions = {
		    position: location,
		    pov: {
		      heading: view.heading,
		      pitch: view.pitch
		    },
		    zoom: view.zoom
		  };
		  var domNode = document.getElementById(view.domid);
		  var pano = new google.maps.StreetViewPanorama(
		      domNode,
		      panoramaOptions);
		  pano.setVisible(true);
	}

}


/**
 * After the Google Maps API has been loaded init the relevant Google maps
 */
function loadShortCodeMaps() {
	for (var i = 0; i < shortcodeMaps.length; i++) {
		map = shortcodeMaps[i];

		// deal with map type
		var maptype = convertMapType(map.maptype);

		// Google Maps API has already been loaded, so init Google Map
		var mapOptions = {
			center: { lat: map.latitude, lng: map.longitude},
			zoom: map.zoom,
			mapTypeId: maptype
		};
		var gmap = new google.maps.Map(document.getElementById(map.domid),
			mapOptions);
		if (map.allowfullscreen == 1) {
			gmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(
				FullScreenControl(gmap, "Full Screen", "Original Size")
			);
		}
	}

}


/**
 * Callback function after the Google Maps API has been loaded - renders the maps along with
 * associated points of interest and layers
 */
function loadedGoogleMapsAPI() {
	loadShortCodeMaps();
	loadShortCodeStreetView();

	for (var i = 1; i <= mappableMapCtr; i++) {
		var mapdomid = 'google_map_' + i;
		var map_info = mappableMaps[mapdomid];
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


		var markers = addAllMarkers(map, map_info.markers, map_info.useClusterer,
			map_info.enableAutomaticCenterZoom, map_info.defaultHideMarker);

		if (map_info.enableAutomaticCenterZoom) {
			centre = map.centreCoordinates;
			map.setCenter(new google.maps.LatLng(centre.lat, centre.lng));

			map_info.minLat = map.minLat;
			map_info.maxLat = map.maxLat;
			map_info.minLng = map.minLng;
			map_info.maxLng = map.maxLng;

			var bds = new google.maps.LatLngBounds(new google.maps.LatLng(map_info.minLat, map_info.minLng),
				new google.maps.LatLng(map_info.maxLat, map_info.maxLng));
			map.fitBounds(bds);
		} else {
			var centre = map_info.centreCoordinates;
			map.setCenter(new google.maps.LatLng(centre.lat, centre.lng));
			map.setZoom(map_info.zoom);
		}

		var googlemaptype = convertMapType(map_info.mapType);
		map.setMapTypeId(googlemaptype);

		if (map_info.useClusterer) {
			var mcOptions = {gridSize: options.clusterergridsize, maxZoom: options.clusterermaxzoom};
			var markerCluster = new MarkerClusterer(map,markers,mcOptions);
		}

		addLines(map, map_info.lines);
		addKmlFiles(map, map_info.kmlFiles);

		var infoWindow = new google.maps.InfoWindow({
			content: ''
		});
		infoWindows[mapdomid] = infoWindow;
	}
}
