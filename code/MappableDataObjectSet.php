<?php

/*
 * Provides a GoogleMap() function to DataObject objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableDataObjectSet extends Extension {

	public function GoogleMap($width = null, $height = null) {
		$gmap = GoogleMapUtil::get_map($this->owner);
		$w = $width ? $width : GoogleMapUtil::$map_width;
		$h = $height ? $height : GoogleMapUtil::$map_height;
		$gmap->setSize($w,$h);
		return $gmap;
	}
	
	
}