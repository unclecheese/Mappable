<?php

class PointsOfInterestAdmin extends ModelAdmin {
	private static $managed_models = array('PointsOfInterestLayer','PointOfInterest');
	private static $url_segment = 'poi';
	private static $menu_title = 'Points of Interest';
	private static $menu_icon = '/mappable/icons/menuicon.png';

	static $has_one = array(
	    'DefaultIcon' => 'Image'
	);
}
