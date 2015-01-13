<?php 

class PointsOfInterestAdmin extends ModelAdmin {
	private static $managed_models = array('PointsOfInterestLayer','PointOfInterest'); // Can manage multiple models
	private static $url_segment = 'poi'; // Linked as /admin/products/
	private static $menu_title = 'Points of Interest';

	static $has_one = array(
	    'DefaultIcon' => 'Image'
	);
}