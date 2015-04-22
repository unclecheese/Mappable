<?php

/*
 * Provides a GoogleMap() function to ViewableData objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableData extends Extension {

	/**
	 * Optional template values for the map info windows
	 */
	private $MarkerTemplateValues = null;

	/**
	 * URL of static maps api
	 * @var string
	 */
	private static $staticmap_api_url = '//maps.googleapis.com/maps/api/staticmap';

	/**
	 * Default zoom for static map
	 * @var int
	 */
	private static $staticmap_default_zoom = 13;

	/**
	 * Pass through values to the markers so that when rendering the map info windows, these
	 * parameters are available to the template.  This is of course optional
	 *
	 * @param array $values hash array of template key to template value
	 */
	public function setMarkerTemplateValues($values) {
		$this->MarkerTemplateValues = $values;
	}


	public function getRenderableMap($width = null, $height = null, $zoom = 9) {
		$gmap = MapUtil::get_map(new ArrayList(array($this->owner)), $this->MarkerTemplateValues);
		$w = $width ? $width : MapUtil::$map_width;
		$h = $height ? $height : MapUtil::$map_height;
		$gmap->setSize($w,$h);
		$gmap->setZoom($zoom);
		$gmap->setEnableAutomaticCenterZoom(false);
		if ($this->owner->MapPinEdited) {
			$gmap->setLatLongCenter(array(
				'200',
				'4',
				$this->owner->getMappableLatitude(),
				$this->owner->getMappableLongitude()
			));
		}

		MapUtil::set_map_already_rendered(true);
		return $gmap;
	}


	/**
	 * returns an <img> with a src set to a static map picture
	 *
	 * You can use MappableData.staticmap_api_url config var to set the domain of the static map.
	 * You can use MappableData.staticmap_default_zoom config var to set the default zoom for the static map.
	 *
	 * @uses Mappable::getMappableMapPin() to draw a special marker, be sure this image is public available
	 *
	 * @param null $width
	 * @param null $height
	 * @return string
	 */
	public function StaticMap($width = null, $height = null) {
		$w = $width ? $width : MapUtil::$map_width;
		$h = $height ? $height : MapUtil::$map_height;
		$lat = $this->owner->getMappableLatitude();
		$lng = $this->owner->getMappableLongitude();
		$pin = $this->owner->getMappableMapPin();

		$apiurl = Config::inst()->get('MappableData', 'staticmap_api_url');

		$urlparts = array(
			'center' => "$lat,$lng",
			'markers' => "$lat,$lng",
			'zoom' => Config::inst()->get('MappableData', 'staticmap_default_zoom'),
			'size' => "${w}x$h",
			'sensor' => 'false' //@todo: make sensor param configurable
		);
		if ($pin) {
			$urlparts['markers'] = "icon:$pin|$lat,$lng";
		}

		$src = htmlentities($apiurl . '?' . http_build_query($urlparts));
		return '<img src="'.$src.'" width="'.$w.'" height="'.$h.'" alt="'.$this->owner->Title.'" />';
	}

}
