#Map Styles
The color of a rendered Google map can be changed by setting style parameters in JSON format.  For
background on this see https://developers.google.com/maps/documentation/javascript/styling

```
		$map->setMapStyle( $jsonStyle );
```

A good source of styles to get started with is https://snazzymaps.com/explore, a worked example
appears below. The only difference with normal map rendering is the addition of the setStyle call.

```php
public function Map() {
		$stations = OpenWeatherMapStation::get();
		$vars = array(
			'Link' => $this->Link()
		);
		$stations->setMarkerTemplateValues($vars);
		$map = $stations->getRenderableMap()->
			setZoom($this->owner->ZoomLevel)->
			setAdditionalCSSClasses('fullWidthMap');
		$map->setEnableAutomaticCenterZoom(true);
		$map->setZoom(10);
		$map->setShowInlineMapDivStyle(true);
		$map->setClusterer(true);
		$map->CurrentURL = $this->Link();

		$style = '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]';

		$map->setMapStyle($style);

		return $map;
	}
```

![Styled Map]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/styledmap.png?raw=true 
Styled Map")
