function createMarker(map,lat, lng, html, category, icon, useClusterer, enableWindowZoom, defaultHideMarker) {
    mapId = map.getDiv().attributes['id'].textContent;
    var marker = new google.maps.Marker();

    console.log("Creating marker for "+mapId+" at "+lat+','+lng);
    marker.setPosition(new google.maps.LatLng(lat, lng));
    marker.mycategory = category;

    if (icon != '') {
        var image = new google.maps.MarkerImage(icon);
        marker.setIcon(image);
    }

    if (useClusterer) {
        fluster.addMarker(marker);
    } else {
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

    console.log("PUSHING MARKER TO "+mapId);
    console.log(gmarkers);
    gmarkers[mapId].push(marker);

    if (defaultHideMarker) {
        console.log("Hide marker");
        marker.hide();
    }

}



// JS public function to get current Lat & Lng

function getCurrentLat() {
    return current_lat;
}

function getCurrentLng() {
    return current_lng;
}



// JS public function to center the gmaps dynamically

function showAddress(address) {
    if (geocoder) {
        geocoder.getLatLng(
        address,

        function(point) {
            if (!point) {
                alert(address + " not found");
            } else {
                map.setCenter(point);
                map.setZoom($Zoom);
            }
        });
    }
}



function addAllMarkers(map, markers, useClusterer, enableWindowZoom, defaultHideMarker) {
    for (var i = 0; i < markers.length; i++) {
        var marker = markers[i];
        createMarker(map,marker.latitude, marker.longitude, marker.html, marker.category, marker.icon,
        useClusterer, enableWindowZoom, defaultHideMarker);
    }

}

function addLines(lines) {
    for (i = 0; i < lines.length; i++) {
        var line = lines[i];
        console.log("LINE:");
        console.log(line);
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

function addKmlFiles(map, kmlFiles) {
    console.log("Adding KML files");
    for (var i = 0; i < kmlFiles.length; i++) {
        var kmlFile = kmlFiles[i];
        console.log("KML FILE:"+kmlFiles);
        var kmlLayer = new google.maps.KmlLayer(kmlFile, {
            suppressInfoWindows: true,
            map: map
        });

        console.log(kmlLayer);
    }
}

function registerMap(googleMapID, centreCoordinates, zoom, minLat, minLng, maxLat, maxLng, mapType, markers, lines, kmlFiles, jsonMapStyles, enableAutomaticCenterZoom, useClusterer) {
    var newMap = new Array();
    newMap['googleMapID'] = googleMapID;
    newMap['zoom'] = zoom;
    newMap['centreCoordinates'] = centreCoordinates;
    newMap['minLat'] = minLat;
    newMap['minLng'] = minLng;
    newMap['maxLng'] = maxLng;
    newMap['markers'] = markers;
    newMap['googleMapID'] = googleMapID;
    newMap['mapType'] = mapType;
    newMap['lines'] = lines;
    newMap['kmlFiles'] = kmlFiles;
    newMap['jsonMapStyles'] = jsonMapStyles;
    newMap['enableAutomaticCenterZoom'] = enableAutomaticCenterZoom;
    newMap['useClusterer'] = useClusterer;
    mappableMaps[googleMapID] = newMap;

    // initialise gmarkers array for this map
    gmarkers[googleMapID] = [];

    gmarkers['wibble'] = googleMapID;

    console.log("GMARKERS ARRAY CREATED FOR "+googleMapID);
    console.log(gmarkers);

    var infoWindow = new google.maps.InfoWindow({ content: 'test', maxWidth: 400 });
    infoWindows[googleMapID] = infoWindow;

    mapLayers[googleMapID] = kmlFiles;

}



function loadedGoogleMapsAPI() {
    console.log("google maps api callback - GMARKERS:");
    console.log(gmarkers);

    for (var i = 1; i <= Object.keys(mappableMaps).length; i++) {
        console.log("MAP LOOP " + i);
        var map_info = mappableMaps['google_map_' + i];
        var map = new google.maps.Map(document.getElementById(map_info.googleMapID));
        console.log(map_info.googleMapID);
        console.log(map_info.centreCoordinates);

        if (map_info.useClusterer) {
            console.log("Using clusterer");
            fluster = new Fluster2(map);
        }
        //  <% end_if %>
        geocoder = new google.maps.Geocoder();

        if (map_info.jsonMapStyles) {
            console.log('JSON MAP STYLES');
            //map.setOptions({styles: map_info.jsonMapStyles});
        };


        if (map_info.enableAutomaticCenterZoom) {
            map.setCenter(new google.maps.LatLng(map_info.centreCoordinates));
            console.log("T1 SET MAP CENTRE TO " + map_info.centreCoordinates);
            var bds = new google.maps.LatLngBounds(new google.maps.LatLng($MinLat, $MinLng),
            new google.maps.LatLng($MaxLat, $MaxLng));
            console.log("BOUNDS");
            console.log(bds);
            map.fitBounds(bds);
            // map.setZoom();
        } else {
            var centre = map_info.centreCoordinates;
            map.setCenter(new google.maps.LatLng(centre.lat,centre.lng));
            map.setZoom(map_info.zoom);
//map.setCenter(new google.maps.LatLng(51.0453246, -114.0581012));

            console.log("T2 SET MAP CENTRE TO " + centre.lat+','+centre.lng);
           // console.log("T2 SET MAP ZOOM TO $Zoom");
        }

        if (map_info.mapType) {
            console.log("MAP TYPE: T1 ");
            map.setMapTypeId(mapType);
        } else {
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        }

        console.log("MARKERS:");
        console.log(map_info.markers);

        //addAllMarkers($MapMarkers,$UseClusterer,$EnableAutomaticCenterZoom, $DefaultHideMarker);
        addAllMarkers(map, map_info.markers, map_info.useClusterer, map_info.enableAutomaticCenterZoom, map_info.defaultHideMarker);
        //addLines($Lines);
        addKmlFiles(map,map_info.kmlFiles);
        if (map_info.useClusterer) {fluster.initialize()};


    };
}