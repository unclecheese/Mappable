<?php

/*
 * Provides a GoogleMap() function to DataObject objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableDataObjectSet extends Extension {

	/**
	 * Optional template values for the map info windows
	 */
	private $MarkerTemplateValues = null;

	/**
	 * Pass through values to the markers so that when rendering the map info windows, these
	 * parameters are available to the template.  This is of course optional
	 *
	 * @param array $values hash array of template key to template value
	 */
	public function setMarkerTemplateValues($values) {
		$this->MarkerTemplateValues = $values;
	}

	public function getRenderableMap($width = null, $height = null) {
		$gmap = MapUtil::get_map($this->owner, $this->MarkerTemplateValues);
		$w = $width ? $width : MapUtil::$map_width;
		$h = $height ? $height : MapUtil::$map_height;
		$gmap->setSize($w,$h);
		return $gmap;
	}

}
