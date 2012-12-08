<?php

/*
 * Provides a GoogleMap() function to ViewableData objects.
 *
 * @author Uncle Cheese
 * @package mappable
 */
class MappableData extends Extension {

	public function GoogleMap($width = null, $height = null, $zoom = 9) {
		$gmap = GoogleMapUtil::get_map(new DataObjectSet($this->owner));
		$w = $width ? $width : GoogleMapUtil::$map_width;
		$h = $height ? $height : GoogleMapUtil::$map_height;
		$gmap->setSize($w,$h);
		$gmap->setZoom($zoom);
		$gmap->setEnableAutomaticCenterZoom(false);
		$gmap->setLatLongCenter(array(
			'200',
			'4',
			$this->owner->getMappableLatitude(),
			$this->owner->getMappableLongitude()
		));
		
		return $gmap;
	}
        public function StaticMap($width = null, $height = null) {
		$w = $width ? $width : GoogleMapUtil::$map_width;
		$h = $height ? $height : GoogleMapUtil::$map_height;

                $lat = $this->owner->getLatitude();
                $lng = $this->owner->getLongitude();

                $src = htmlentities("http://maps.google.com/maps/api/staticmap?center=$lat,$lng&markers=$lat,$lng&zoom=13&size=${w}x$h&sensor=false");

                return '<img src="'.$src.'" width="'.$w.'" height="'.$h.'" alt="'.$this->owner->Title.'" />';

	}

}