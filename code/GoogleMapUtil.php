<?php

class GoogleMapUtil
{

	
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

        /** @var int Icon width of the gmarker **/
        public static $iconWidth = 24;

        /** @var int Icon height of the gmarker **/
        public static $iconHeight = 24;
		
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
         * @var string $center Center of map (adress)
         */
	public static $center = 'Paris, France';

        /**
         * @var int info_window_width Width of info window
         */

        public static $info_window_width = 250;
	
	
	
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
          * Set the with of the gmap infowindow (on marker clik)
          *
          * @param int $info_window_width GoogleMap info window width
          *
          * @return void
          */
        public function set_info_window_width($info_window_width)
        {
            self::$info_window_width = $info_window_width;
        }

        /**
          * Set the center of the gmap (an address)
          *
          * @param string $center GoogleMap  center (an address)
          *
          * @return void
          */
        public function set_center($center)
        {
            self::$center = $center;
        }

        /**
          * Set the size of the icon markers
          *
          * @param int $iconWidth GoogleMap  marker icon width
          * @param int $iconHeight GoogleMap  marker icon height
          *
          * @return void
          */

        public function set_icon_size($iconWidth,$iconHeight)
        {
            self::$iconWidth = $iconWidth;
            self::$iconHeight = $iconHeight;
        }
	/**
	 * Get a new GoogleMapAPI object and load it with the default settings
	 *
	 * @return GoogleMapAPI
	 */
	public static function instance()
	{
		self::$instances++;
		$gmap = new GoogleMapAPI(self::$div_id."_".self::$instances);
		$gmap->setWidth(self::$map_width);
		$gmap->setHeight(self::$map_height);
        $gmap->setMapType(self::$map_type);
		return $gmap;
	}


	/**
	 * Sanitize a string of HTML content for safe inclusion in the JavaScript
	 * for a Google Map
	 *
	 * DEPRECATED
	 *
	 * @return string
	 */
	public static function sanitize($content) {
		return $content;
		//return addslashes(str_replace(array("\n","\r"),array("",""),$content));	
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


    /**
     * get distance between to geocoords using great circle distance formula
     * 
     * @param float $lat1
     * @param float $lat2
     * @param float $lon1
     * @param float $lon2
     * @param float $unit   M=miles, K=kilometers, N=nautical miles, I=inches, F=feet
     * @return float
     */
    public static function geo_geo_distance($lat1,$lon1,$lat2,$lon2,$unit='M') {
        
      // calculate miles
      $M =  69.09 * rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2)))); 

      switch(strtoupper($unit))
      {
        case 'K':
          // kilometers
          return $M * 1.609344;
          break;
        case 'N':
          // nautical miles
          return $M * 0.868976242;
          break;
        case 'F':
          // feet
          return $M * 5280;
          break;            
        case 'I':
          // inches
          return $M * 63360;
          break;            
        case 'M':
        default:
          // miles
          return $M;
          break;
      }
      
    }    


		
}