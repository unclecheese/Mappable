<?php

class GoogleMapUtil
{

	/**
	 * @var string The Google Maps API key
	 */
	protected static $api_key;
	
	
	
	/**
	 * @var int Number of active {@see GoogleMapsAPI} instances (for the HTML ID)
	 */
	protected static $instances = 0;


	/**
	 * @var int The default width of a Google Map
	 */
	public static $map_width = 400;
	
	
	/**
	 * @var int The default height of a Google Map
	 */
	public static $map_height = 400;

		
	/**
	 * @var int Prefix for the div ID of the map
	 */
	public static $div_id = "google_map";
	
	
	/**
	 * @var boolean Automatic center/zoom for the map
	 */
	public static $automatic_center = true;
	
	
	/**
	 * @var boolean Show directions fields on the map
	 */
	public static $direction_fields = false;
	
	
	/**
	 * @var boolean Show the marker fields on the map
	 */
	public static $hide_marker = false;

	/**
	 * @var boolean Show the marker fields on the map
	 */
	public static $map_type = 'G_NORMAL_MAP';
	
	
	
	/**
	 * Set the API key for Google Maps
	 *
	 * @param string $key 
	 */
	public static function set_api_key($key) {
		self::$api_key = $key;
	}
	
	
	/**
	 * Set the default size of the map
	 *
	 * @param int $width
	 * @param int $height
	 */
	public static function set_map_size($width, $height) {
		self:: $map_width = $width;
		self::$map_height = $height;
	}
	
        /**
          * Set the type of the gmap
          *
          * @param string $mapType ( can be 'G_NORMAL_MAP', 'G_SATELLITE_MAP', 'G_HYBRID_MAP', 'G_PHYSICAL_MAP')
          *
          * @return void
          */

        public function set_map_type($mapType)
        {
            self::$map_type = $mapType;
        }	
	
	/**
	 * Get a new GoogleMapAPI object and load it with the default settings
	 *
	 * @return GoogleMapAPI
	 */
	public static function instance()
	{
		self::$instances++;
		$gmap = new GoogleMapAPI(self::$api_key);
		$gmap->setDivId(self::$div_id."_".self::$instances);
		$gmap->setEnableAutomaticCenterZoom(self::$automatic_center);
		$gmap->setDisplayDirectionFields(self::$direction_fields);
		$gmap->setSize(self::$map_width, self::$map_height);
		$gmap->setDefaultHideMarker(self::$hide_marker);
                $gmap->setMapType(self::$map_type);
		return $gmap;
	}


	/**
	 * Sanitize a string of HTML content for safe inclusion in the JavaScript
	 * for a Google Map
	 *
	 * @return string
	 */
	public static function sanitize($content) {
		return addslashes(str_replace(array("\n","\r"),array("",""),$content));	
	}
	
	
	/**
	 * Creates a new {@link GoogleMapsAPI} object loaded with the default settings
	 * and places all of the items in a {@link DataObjectSet} on the map
	 *
	 * @param DataObjectSet $set
	 * @return GoogleMapsAPI
	 */
	public static function get_map(DataObjectSet $set) {
		$gmap = self::instance();
		if($set) {
		    foreach($set as $obj) {
		    	$gmap->addMarkerAsObject($obj);
		    }
		}
		return $gmap;	
	}
		
}