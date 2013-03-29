<?php

/*
 * Provides a GoogleMap() function to DataObject objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableDataObjectSet extends Extension {

	public function getRenderableMap($width = null, $height = null) {
		$gmap = MapUtil::get_map($this->owner);
		$w = $width ? $width : MapUtil::$map_width;
		$h = $height ? $height : MapUtil::$map_height;
		$gmap->setSize($w,$h);
		return $gmap;
	}
	
	
}