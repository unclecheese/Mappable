<?php

/*
 * Provides a GoogleMap() function to ViewableData objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableData extends Extension {

	public function GoogleMap($width = null, $height = null) {
		$gmap = GoogleMapUtil::get_map(new DataObjectSet($this->owner));
		$w = $width ? $width : GoogleMapUtil::$map_width;
		$h = $height ? $height : GoogleMapUtil::$map_height;
		$gmap->setSize($w,$h);
		$gmap->setEnableAutomaticCenterZoom(false);
		$gmap->setLatLongCenter(array(
			'200',
			'4',
			$this->owner->getLatitude(),
			$this->owner->getLongitude()
		));
		
		return $gmap;
	}
	

}