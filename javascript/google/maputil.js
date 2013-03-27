function createMarker(lat, lng, html, category, icon, useClusterer, enableWindowZoom, defaultHideMarker) {
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
           map.setCenter(new google.maps.LatLng(lat, lng), $InfoWindowZoom); 
        }
        infoWindow.setContent(html);
        infoWindow.open(map, this);
    });

    gmarkers.push(marker);

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


//function createMarker(lat, lng, html, category, icon, useClusterer, enableWindowZoom, defaultHideMarker) {

function addAllMarkers(markers, useClusterer, enableWindowZoom, defaultHideMarker) {
    for (var i = 0; i < markers.length; i++) {
        var marker = markers[i];
        createMarker(marker.latitude, marker.longitude, marker.html, marker.category, marker.icon,
            useClusterer, enableWindowZoom, defaultHideMarker
        );
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

function addKmlFiles(kmlFiles) {
    for (var i = 0; i < kmlFiles.length; i++) {
        var kmlFile = kmlFiles[i];
        var kmlLayer = new google.maps.KmlLayer(kmlFile, {
            suppressInfoWindows: true,
            map: map
        });
    }
}