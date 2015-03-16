<?php

class PointsOfInterestAdmin extends ModelAdmin {
	private static $managed_models = array('PointsOfInterestLayer','PointOfInterest');
	private static $url_segment = 'poi';
	private static $menu_title = 'Points of Interest';

	static $has_one = array(
	    'DefaultIcon' => 'Image'
	);
}
