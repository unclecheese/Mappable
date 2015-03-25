function initialize() {
	var mapOptions = {
		center: { lat: $Latitude, lng: $Longitude},
		zoom: $Zoom,
		mapTypeId: google.maps.MapTypeId.$MapType
	};
	var map = new google.maps.Map(document.getElementById('$DomID'),
		mapOptions);
	if ($AllowFullScreen == 1) {
		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
			FullScreenControl(map, "Full Screen", "Original Size")
		);
	}
}
google.maps.event.addDomListener(window, 'load', initialize);
