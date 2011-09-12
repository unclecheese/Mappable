var marker;
(function($) {
	$(function() {
		$('.geocode_button').live("click", function() {
			var $t = $(this);
			var address_fields = $t.metadata().aFields;
			var parts = address_fields.split(',');
			var address_values = [];
			for(var i=0; i < parts.length; i++) {
				address_values.push($('[name='+parts[i]+']').val());
			}
			$.get(
				$t.attr('href'),
				{address: address_values.join(" " )},
				function(data) {
					var loc = data.split(",");
					var $lat = $('[name='+$t.metadata().lat+']');
					var $long = $('[name='+$t.metadata().long+']');
					$lat.val(loc[0]);
					$long.val(loc[1]);
					if($lat.siblings('span.readonly').length) {
						$lat.siblings('span.readonly').text(loc[0]);
					}
					if($long.siblings('span.readonly').length) {
						$long.siblings('span.readonly').text(loc[1]);
					}
                                        
                                        //update map
                                        var point = new GLatLng($lat.val(), $long.val());
                                        marker.setPoint(point);
                                        map.setCenter(point,16);

				}
			);
			return false;
		});
                var $t = $('.geocode_button');
                var $lat = $('[name='+$t.metadata().lat+']');
                var $long = $('[name='+$t.metadata().long+']');
                var center = new GLatLng($lat.val(), $long.val());
                marker = new GMarker(center, {draggable: true});
                map.setCenter(center,16);
                map.addOverlay(marker);
                GEvent.addListener(marker, "dragend", function(overlay, point) {
                    var point = marker.getLatLng();
                    map.setCenter(point);
                    var $lat = $('[name='+$t.metadata().lat+']');
                    var $long = $('[name='+$t.metadata().long+']');
                    $lat.val(point.y);
                    $long.val(point.x);
                    if($lat.siblings('span.readonly').length) {
                            $lat.siblings('span.readonly').text(point.y);
                    }
                    if($long.siblings('span.readonly').length) {
                            $long.siblings('span.readonly').text(point.x);
                    }

                });
	});
})(jQuery);
