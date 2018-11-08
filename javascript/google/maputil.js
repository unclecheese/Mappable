var primeMap;

(function($) {

	// FIXME - make non global
	var infoWindows = [];

	/**
	 * Create a google map pin from data provided
	 * @param  {GoogleMap} map             Instance of a google map
	 * @param  {Number} lat                Latitude of pin
	 * @param  {Number} lng                Longitude of pin
	 * @param  {String} html               HTML for information window
	 * @param  {String} icon               URL of alternative map icon, or blank for default
	 * @param  {boolean} useClusterer      Whether or not to use clusterer
	 * @param  {boolean} enableWindowZoom  Whether or not to enable zoom on the rendered map
	 * @param  {boolean} infoWindowZoom    FIXME: To do
	 * @param  {boolean} defaultHideMarker Whether or not to hide markers initially
	 * @return {MapMarker}                 Google map pin object
	 */
	function createMarker(map, lat, lng, html, category, icon, useClusterer, enableWindowZoom,
		infoWindowZoom, defaultHideMarker) {

		mapId = map.getDiv().getAttribute('id');

		var marker = new google.maps.Marker();
		marker.setPosition(new google.maps.LatLng(lat, lng));
		marker.mycategory = category;

		if (icon && icon !== '') {
			var image = new google.maps.MarkerImage(icon);
			marker.setIcon(image);
		}

		if (!(useClusterer == '1')) {
			marker.setMap(map);
		}

		google.maps.event.addListener(marker, "click", function() {
			if (enableWindowZoom) {
				map.setCenter(new google.maps.LatLng(lat, lng), infoWindowZoom);
			}
			var infoWindow = infoWindows[mapId];
			infoWindow.setContent(html);
			infoWindow.open(map, this);
		});

		//FIXME gmarkers[mapId].push(marker);
		console.log(defaultHideMarker === false);
		if (defaultHideMarker == '1') {
			marker.setVisible(false);
		} else {
			marker.setVisible(true);
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
	function addAllMarkers(map, markers, useClusterer, enableWindowZoom, infoWindowZoom, defaultHideMarker) {

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
				infoWindowZoom, defaultHideMarker);

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
		centreCoordinates.lat = (parseFloat(map.minLat) + parseFloat(map.maxLat)) / 2;
		centreCoordinates.lng = (parseFloat(map.minLng) + parseFloat(map.maxLng)) / 2;
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
	 * After the Google Maps API has been loaded init the relevant Google maps
	 */
	function loadShortCodeStreetView() {

		var svs = $('div[data-streetview]');

		svs.each(function(index) {
			var svnode = $(this);
			var svnodeid = svnode.attr('id');
			var lat = parseFloat(svnode.attr('data-latitude'));
			var lon = parseFloat(svnode.attr('data-longitude'));
			var zoom = parseInt(svnode.attr('data-zoom'));
			var heading = parseFloat(svnode.attr('data-heading'));
			var pitch = parseFloat(svnode.attr('data-pitch'));

			var location = new google.maps.LatLng(lat, lon);

			var panoramaOptions = {
				position: location,
				pov: {
					heading: heading,
					pitch: pitch
				},
				zoom: zoom
			};


			var domNode = document.getElementById(svnodeid);
			var pano = new google.maps.StreetViewPanorama(
				domNode,
				panoramaOptions);
			pano.setVisible(true);
		});



	}


	/**
	 * After the Google Maps API has been loaded init the relevant Google maps
	 */
	function loadShortCodeMaps() {

		var scms = $('div[data-shortcode-map]');

		scms.each(function(index) {
			var scmnode = $(this);
			var scmnodeid = scmnode.attr('id');
			var maptype = convertMapType(scmnode.attr('data-maptype'));
			var lat = parseFloat(scmnode.attr('data-latitude'));
			var lng = parseFloat(scmnode.attr('data-longitude'));
			var zoom = parseInt(scmnode.attr('data-zoom'));
			var allowfullscreen = parseInt(scmnode.attr('data-allowfullscreen'));

			// Google Maps API has already been loaded, so init Google Map
			var mapOptions = {
				center: {
					lat: lat,
					lng: lng
				},
				zoom: zoom,
				mapTypeId: maptype
			};



			var gmap = new google.maps.Map(document.getElementById(scmnodeid),
				mapOptions);
			if (allowfullscreen == 1) {
				gmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(
					FullScreenControl(gmap, "Full Screen", "Original Size")
				);
			}
		});


	}


	/**
	 * Callback function after the Google Maps API has been loaded - renders the maps along with
	 * associated points of interest and layers
	 */
	primeMap = function loadedGoogleMapsAPI() {
		loadShortCodeMaps();
		loadShortCodeStreetView();

		var maps = $('div[data-map]');

		maps.each(function(index) {
			var mapnode = $(this);
			mapdomid = mapnode.attr('id');
			var map = new google.maps.Map(document.getElementById(mapdomid));

			// initialise geocoder
			geocoder = new google.maps.Geocoder();

			// default of [] renders google maps as per normal
			if (mapnode.attr('data-mapstyles')) {
				var json = $.parseJSON(mapnode.attr('data-mapstyles'));
				map.setOptions({
					styles: json
				});
			}

			if (mapnode.data['data-allowfullscreen']) {
				map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
					FullScreenControl(map, "Full Screen", "Original Size")
				);
			}

			var markerjson = $.parseJSON(mapnode.attr('data-mapmarkers'));
			var useClusterer = mapnode.attr('data-useclusterer');
			var enableAutomaticCenterZoom = mapnode.attr('data-enableautocentrezoom');
			var infoWindowZoom = mapnode.attr('data-infowindowzoom');
			var defaultHideMarker = mapnode.attr('data-defaulthidemarker');
			var markers = addAllMarkers(map, markerjson, useClusterer,
				enableAutomaticCenterZoom, infoWindowZoom, defaultHideMarker);
			var allowfullscreen = parseInt(mapnode.attr('data-allowfullscreen'));

			if (enableAutomaticCenterZoom == 1) {
				centre = $.parseJSON(mapnode.attr('data-centre'));
				map.setCenter(new google.maps.LatLng(centre.lat, centre.lng));

				var bds = new google.maps.LatLngBounds(new google.maps.LatLng(map.minLat, map.minLng),
					new google.maps.LatLng(map.maxLat, map.maxLng));
				map.fitBounds(bds);
			} else {
				centre = $.parseJSON(mapnode.attr('data-centre'));
				map.setCenter(new google.maps.LatLng(centre.lat, centre.lng));
				var zoom = parseInt(mapnode.attr('data-zoom'));
				map.setZoom(zoom);
			}

			if (allowfullscreen == 1) {
				map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
					FullScreenControl(map, "Full Screen", "Original Size")
				);
			}

			var googlemaptype = convertMapType(mapnode.attr('data-maptype'));
			map.setMapTypeId(googlemaptype);

			if (useClusterer == 1) {
				// ensure zoom and grid size are integers by prefixing with unary plus
				var clustererGridSize = parseInt(mapnode.attr('data-clusterergridsize'));
				var clustererMaxZoom = parseInt(mapnode.attr('data-clusterermaxzoom'));
				var mcOptions = {
					gridSize: clustererGridSize,
					maxZoom: clustererMaxZoom
				};
				var markerCluster = new MarkerClusterer(map, markers, mcOptions);
			}

			var lines = $.parseJSON(mapnode.attr('data-lines'));
			addLines(map, lines);

			var kmlFiles = $.parseJSON(mapnode.attr('data-kmlfiles'));
			addKmlFiles(map, kmlFiles);

			var infoWindow = new google.maps.InfoWindow({
				content: ''
			});
			infoWindows[mapdomid] = infoWindow;

			// trigger an event now that the map has been initialised
			// Use this for example to add listeners to the map from another JS file
			mapnode.trigger("mapInitialised", [map]);
		});
	};



	function nearestPOIs() {

		// normally will only be one
		var nears = $('div[data-nearest-poi]');

		nears.each(function(index) {
			var nearnode = $(this);
			var layerID = nearnode.attr('data-layer-id');

			if (geoPosition.init()) { // Geolocation Initialisation
				geoPosition.getCurrentPosition(success_callback, error_callback, {
					enableHighAccuracy: true
				});
			} else {
				alert('your location is not available');
			}
			//geoPositionSimulator.init();
		});
	}


	function success_callback(p) {
		console.log(p.coords);
		// p.latitude : latitude value
		// p.longitude : longitude value
		//
		var url = window.location;
		url = url + 'find?lat=' + p.coords.latitude + '&lng=' + p.coords.longitude;
		window.location = url;
	}


	function error_callback(p) {
		alert('error)');
	}

	window.loadGoogleMapsScript = function() {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = 'https://maps.googleapis.com/maps/api/js?' +
			'&sensor=false&callback=loadedGoogleMapsAPI&hl=en';
		document.body.appendChild(script);
	};


	window.addEventListener ?
		window.addEventListener("load", loadGoogleMapsScript, false) :
		window.attachEvent && window.attachEvent("onload", loadGoogleMapsScript);
})(jQuery);


function loadedGoogleMapsAPI() {
	primeMap();
}
