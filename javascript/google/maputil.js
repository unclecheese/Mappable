function createMarker(map,lat, lng, html, category, icon, useClusterer, enableWindowZoom, defaultHideMarker) {
    mapId = map.getDiv().attributes['id'].textContent;
    var marker = new google.maps.Marker();

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

    gmarkers[mapId].push(marker);

    if (defaultHideMarker) {
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

function addKmlFiles(map, kmlFiles) {
    for (var i = 0; i < kmlFiles.length; i++) {
        var kmlFile = kmlFiles[i];
        var kmlLayer = new google.maps.KmlLayer(kmlFile, {
            suppressInfoWindows: true,
            map: map
        });

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
    newMap['maxLat'] = maxLat;
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
    var infoWindow = new google.maps.InfoWindow({ content: 'test', maxWidth: 400 });
    infoWindows[googleMapID] = infoWindow;

    mapLayers[googleMapID] = kmlFiles;
    mapLines[googleMapID] = lines;

}



function loadedGoogleMapsAPI() {
    for (var i = 1; i <= Object.keys(mappableMaps).length; i++) {
        var map_info = mappableMaps['google_map_' + i];
        var map = new google.maps.Map(document.getElementById(map_info.googleMapID));
    
        if (map_info.useClusterer) {
            fluster = new Fluster2(map);
        }
        //  <% end_if %>
        geocoder = new google.maps.Geocoder();

        // FIXME - to do
        // if (map_info.jsonMapStyles) {
            //map.setOptions({styles: map_info.jsonMapStyles});
        //};


        if (map_info.enableAutomaticCenterZoom) {
            centre = map_info.centreCoordinates;
            map.setCenter(new google.maps.LatLng(centre.lat,centre.lng));
               
            var bds = new google.maps.LatLngBounds(new google.maps.LatLng(map_info.minLat, map_info.minLng),
                new google.maps.LatLng(map_info.maxLat, map_info.maxLng));
            map.fitBounds(bds);
            
            map.setZoom(map_info.zoom);
        } else {
            var centre = map_info.centreCoordinates;
            map.setCenter(new google.maps.LatLng(centre.lat,centre.lng));
            map.setZoom(map_info.zoom);
        }

        if (map_info.mapType) {
            map.setMapTypeId(map_info.mapType);
        } else {
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        }

        addAllMarkers(map, map_info.markers, map_info.useClusterer, map_info.enableAutomaticCenterZoom, map_info.defaultHideMarker);
        addLines(map, map_info.lines);
        addKmlFiles(map,map_info.kmlFiles);
        if (map_info.useClusterer) {
            fluster.initialize()
        };


    };
}