<?php

/*
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
*
*  @author            CERDAN Yohann <cerdanyohann@yahoo.fr>
*  @copyright      (c) 2009  CERDAN Yohann, All rights reserved
*  @ version         18:13 26/05/2009
*/

class GoogleMapAPI extends ViewableData
{

    /** GoogleMap key **/
   protected $googleMapKey = '';

    /** GoogleMap ID for the HTML DIV  **/
   protected $googleMapId = 'googlemapapi';
    
    /** GoogleMap  Direction ID for the HTML DIV **/
   protected $googleMapDirectionId = 'route';

    /** Width of the gmap **/
   protected $width = 800;

    /** Height of the gmap **/
   protected $height = 600;

    /** Icon width of the gmarker **/
   protected $iconWidth = 24;

    /** Icon height of the gmarker **/
   protected $iconHeight = 24;

    /** Infowindow width of the gmarker **/
   protected $infoWindowWidth = 250;
    
    /** Default zoom of the gmap **/
   protected $zoom = 9;
    
	/** Enable the zoom of the Infowindow **/
   protected $enableWindowZoom = false;
   
	/** Default zoom of the Infowindow **/
   protected $infoWindowZoom = 13;
   
    /** Lang of the gmap **/
   protected $lang = 'en';

    /**Center of the gmap **/
   protected $center = 'Paris, France';

	protected $latLongCenter = null;
	
    /** Content of the HTML generated **/
   protected $content = '';

    /** Add the direction button to the infowindow **/
   protected $displayDirectionFields = false;

    /** Hide the marker by default **/
   protected $defaultHideMarker = false;

    /** Extra content (marker, etc...) **/
   protected $contentMarker = '';

    /** Use clusterer to display a lot of markers on the gmap **/
   protected $useClusterer = false;
   protected $gridSize = 100;
   protected $maxZoom = 9;
   protected $clustererLibrarypath = 'markerclusterer_packed.js';

    /** Enable automatic center/zoom **/
   protected $enableAutomaticCenterZoom = false;
	
    /** maximum longitude of all markers **/
   protected $maxLng = -1000000;
    
    /** minimum longitude of all markers **/
   protected $minLng = 1000000;
    
    /** max latitude of all markers **/
   protected $maxLat = -1000000;
    
    /** min latitude of all markers **/
   protected $minLat = 1000000;
   
    /** map center latitude (horizontal), calculated automatically as markers are added to the map **/
   protected $centerLat = null;

    /** map center longitude (vertical),  calculated automatically as markers are added to the map **/
   protected $centerLng = null;
   
    /** factor by which to fudge the boundaries so that when we zoom encompass, the markers aren't too close to the edge **/
   protected $coordCoef = 0.01;
   
   protected $lines = array ();

   protected $contentLines = '';
   
   protected static $jsIncluded = false;
   

    /**
          * Class constructor
          * 
          * @param string $googleMapKey the googleMapKey
          * 
          * @return void
          */

    public function __construct($googleMapKey='') 
    {
        $this->googleMapKey = $googleMapKey;
    }

    /**
          * Set the key of the gmap
          * 
          * @param string $googleMapKey the googleMapKey
          * 
          * @return void
          */

    public function setKey($googleMapKey) 
    {
        $this->googleMapKey = $googleMapKey;
    }

    /**
          * Set the useClusterer parameter (optimization to display a lot of marker)
          * 
          * @param boolean $useClusterer use cluster or not
          * @param string $clusterIcon the cluster icon
          * @param int $maxVisibleMarkers max visible markers
          * @param int $gridSize grid size
          * @param int $minMarkersPerClusterer minMarkersPerClusterer
          * @param int $maxLinesPerInfoBox maxLinesPerInfoBox
          * 
          * @return void
          */

    public function setClusterer($useClusterer,$gridSize=100,$maxZoom=9,$clustererLibraryPath='markerclusterer_packed.js') 
    {
        $this->useClusterer = $useClusterer;
        $this->gridSize = $gridSize;
        $this->maxZoom = $maxZoom;
		$this->clustererLibraryPath = $clustererLibraryPath;
    }

    /**
          * Set the ID of the default gmap DIV
          * 
          * @param string $googleMapId the google div ID
          * 
          * @return void
          */

    public function setDivId($googleMapId) 
    {
        $this->googleMapId = $googleMapId;
    }
    
    /**
          * Set the ID of the default gmap direction DIV 
          * 
          * @param string $googleMapDirectionId GoogleMap  Direction ID for the HTML DIV
          * 
          * @return void
          */

    public function setDirectionDivId($googleMapDirectionId) 
    {
        $this->googleMapDirectionId = $googleMapDirectionId;
    }

    /**
          * Set the size of the gmap
          * 
          * @param int $width GoogleMap  width
          * @param int $height GoogleMap  height
          * 
          * @return void
          */

    public function setSize($width,$height) 
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
          * Set the with of the gmap infowindow (on marker clik)
          * 
          * @param int $infoWindowWidth GoogleMap  info window width
          * 
          * @return void
          */
    
    public function setInfoWindowWidth ($infoWindowWidth) 
    {
        $this->infoWindowWidth = $infoWindowWidth;
    }
    
    /**
          * Set the size of the icon markers
          * 
          * @param int $iconWidth GoogleMap  marker icon width
          * @param int $iconHeight GoogleMap  marker icon height
          * 
          * @return void
          */

    public function setIconSize($iconWidth,$iconHeight) 
    {
        $this->iconWidth = $iconWidth;
        $this->iconHeight = $iconHeight;
    }

    /**
          * Set the lang of the gmap
          * 
          * @param string $lang GoogleMap  lang : fr,en,..
          * 
          * @return void
          */

    public function setLang($lang) 
    {
        $this->lang = $lang;
    }
    
    /**
          * Set the zoom of the gmap
          * 
          * @param int $zoom GoogleMap  zoom.
          * 
          * @return void
          */

    public function setZoom($zoom) 
    {
        $this->zoom = $zoom;
    }
	
	/**
          * Set the zoom of the infowindow
          * 
          * @param int $zoom GoogleMap  zoom.
          * 
          * @return void
          */

    public function setInfoWindowZoom($infoWindowZoom) 
    {
        $this->infoWindowZoom = $infoWindowZoom;
    }
	
	/**
          * Enable the zoom on the marker when you click on it
          * 
          * @param int $zoom GoogleMap  zoom.
          * 
          * @return void
          */

    public function setEnableWindowZoom($enableWindowZoom) 
    {
        $this->enableWindowZoom = $enableWindowZoom;
    }
	
	/**
          * Enable theautomatic center/zoom at the gmap load
          * 
          * @param int $zoom GoogleMap  zoom.
          * 
          * @return void
          */

    public function setEnableAutomaticCenterZoom($enableAutomaticCenterZoom) 
    {
        $this->enableAutomaticCenterZoom = $enableAutomaticCenterZoom;
    }
	
    /**
          * Set the center of the gmap (an address)
          * 
          * @param string $center GoogleMap  center (an address)
          * 
          * @return void
          */

    public function setCenter($center) 
    {
        $this->center = $center;
    }

    public function setLatLongCenter($center) 
    {
        $this->latLongCenter = $center;
    }

    /**
          * Set the center of the gmap
          * 
          * @param boolean $displayDirectionFields display directions or not in the info window
          * 
          * @return void
          */

    public function setDisplayDirectionFields($displayDirectionFields) 
    {
        $this->displayDirectionFields = $displayDirectionFields;
    }

    /**
          * Set the defaultHideMarker
          * 
          * @param boolean $defaultHideMarker hide all the markers on the map by default
          * 
          * @return void
          */

    public function setDefaultHideMarker($defaultHideMarker) 
    {
        $this->defaultHideMarker = $defaultHideMarker;
    }

    /**
          * Get the google map content
          * 
          * @return string the google map html code
          */

    public function getGoogleMap() 
    {
        return $this->content;
    }
    
    /**
           * Get URL content using cURL.
          * 
          * @param string $url the url 
          * 
          * @return string the html code
          *
          * @todo add proxy settings
          */
		  
    public function getContent($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close ($curl);
        return $data;
    }
    
    /**
          * Geocoding an address (address -> lat,lng)
          * 
          * @param string $address an address
          * 
          * @return array array with precision, lat & lng
          */

    public function geocoding($address) 
    {
        $encodeAddress = urlencode($address);
        $url = "http://maps.google.com/maps/geo?q=".$encodeAddress."&output=csv&key=".$this->googleMapKey;
        
        if(function_exists('curl_init')) {
            $data = $this->getContent($url);
        } else {
            $data = file_get_contents($url);
        }
        
		$csvSplit = preg_split("/,/",$data);
        $status = $csvSplit[0];

        if (strcmp($status, "200") == 0) {
            $return = $csvSplit; // successful geocode, $precision = $csvSplit[1],$lat = $csvSplit[2],$lng = $csvSplit[3];
        } else {
            $return = null; // failure to geocode
        }

        return $return;
    }

    /**
          * Add marker by his coord
          * 
          * @param string $lat lat
          * @param string $lng lngs
          * @param string $html html code display in the info window
          * @param string $category marker category
          * @param string $icon an icon url
          * 
          * @return void
          */

    public function addMarkerByCoords($lat,$lng,$html='',$category='',$icon='') 
    {
		// Save the lat/lon to enable the automatic center/zoom
		$this->maxLng = (float) max((float)$lng, $this->maxLng);
        $this->minLng = (float) min((float)$lng, $this->minLng);
        $this->maxLat = (float) max((float)$lat, $this->maxLat);
        $this->minLat = (float) min((float)$lat, $this->minLat);
        $this->centerLng = (float) ($this->minLng + $this->maxLng) / 2;
        $this->centerLat = (float) ($this->minLat + $this->maxLat) / 2;
		
        $this->contentMarker .= "\t\t\t".'createMarker('.$lat.','.$lng.',"'.$html.'","'.$category.'","'.$icon.'");'."\n";
    }

    /**
          * Add marker by his address
          * 
          * @param string $address an ddress
          * @param string $content html code display in the info window
          * @param string $category marker category
          * @param string $icon an icon url
          * 
          * @return void
          */

    public function addMarkerByAddress($address,$content='',$category='',$icon='') 
    {
        $point = $this->geocoding($address);
		if ($point!==null) {
        $this->addMarkerByCoords($point[2], $point[3], $content, $category, $icon);
		} else {
			// throw new Exception('Adress not found : '.$address);
		}
    }

    /**
          * Add marker by an array of coord
          * 
          * @param string $coordtab an array of lat,lng,content
          * @param string $category marker category
          * @param string $icon an icon url
          * 
          * @return void
          */

    public function addArrayMarkerByCoords($coordtab,$category='',$icon='') 
    {
        foreach ($coordtab as $coord) {
            $this->addMarkerByCoords($coord[0], $coord[1], $coord[2], $category, $icon);
        }
    }
    
    
    /**
     * Adds a {@link ViewableData} object that implements {@link Mappable}
     * to the map.
     *
     * @param ViewableData $obj
     */
    public function addMarkerAsObject(ViewableData $obj) {    
    	if($obj instanceof Mappable) {
        	if(($obj->getLatitude() > 0) || ($obj->getLongitude() > 0)) {
        		$cat = $obj->hasMethod('getMapCategory') ? $obj->getMapCategory() : "default";
		        $this->addMarkerByCoords($obj->getLatitude(), $obj->getLongitude(), $obj->getMapContent(), $cat, $obj->getMapPin());
	        }
        }    
    }
    
    
    /**
     * Draws a line between two {@link ViewableData} objects
     *
     * @param ViewableData $one The first point
     * @param ViewableData $two The second point
     * @param string $color The hexidecimal color of the line
     */
    public function connectPoints(ViewableData $one, ViewableData $two, $color = "#FF3300") {
		$this->addLine(
			array($one->getLatitude(), $one->getLongitude()),
			array($two->getLatitude(), $two->getLongitude()),
			$color
		);    
    }
    
    
    public function forTemplate() {
    	$this->generate();
    	return $this->getGoogleMap();
    }
    
    

    /**
          * Add marker by an array of address
          * 
          * @param string $coordtab an array of address
          * @param string $category marker category
          * @param string $icon an icon url
          * 
          * @return void
          */

    public function addArrayMarkerByAddress($coordtab,$category='',$icon='') 
    {
        foreach ($coordtab as $coord) {
            $this->addMarkerByAddress($coord[0], $coord[1], $category, $icon);
        }
    }

    /**
          * Set a direction between 2 addresss and set a text panel
          * 
          * @param string $from an address
          * @param string $to an address
          * @param string $idpanel id of the div panel
          * 
          * @return void
          */

    public function addDirection($from,$to,$idpanel='') 
    {
        $this->contentMarker .= 'addDirection("'.$from.'","'.$to.'","'.$idpanel.'");';
    }

    /**
          * Parse a KML file and add markers to a category
          * 
          * @param string $url url of the kml file compatible with gmap and gearth
          * @param string $category marker category
          * @param string $icon an icon url
          * 
          * @return void
          */

    public function addKML ($url,$category='',$icon='') 
    {
        $xml = new SimpleXMLElement($url, null, true);
        foreach ($xml->Document->Folder->Placemark as $item) {
            $coordinates = explode(',', (string) $item->Point->coordinates);
            $name = (string) $item->name;
            $this->addMarkerByCoords($coordinates[1], $coordinates[0], $name, $category, $icon);
        }
    }


	public function addLine($from = array(), $to = array(), $color = "#FF3300") {
		$this->contentLines .= "var points = [new GLatLng({$from[0]},{$from[1]}), new GLatLng({$to[0]},{$to[1]})];\n";
		$this->contentLines .= "map.addOverlay(new GPolyline(points,'{$color}',4,0.6));\n";
	}
    /**
          * Initialize the javascript code
          * 
          * @return void
          */

	public function includeGMapsJS() {
		if(self::$jsIncluded) return;
        // Google map JS
        $this->content .= '<script src="http://maps.google.com/maps?hl='. $this->lang.'&file=api&amp;v=2&amp;key='.$this->googleMapKey.'" type="text/javascript">';
        $this->content .= '</script>'."\n";
        
        // Clusterer JS
        if ($this->useClusterer==true) {
			// Source: http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/src/
			$this->content .= '<script src="'.$this->clustererLibraryPath.'" type="text/javascript"></script>'."\n";
        }
        
        self::$jsIncluded = true;
	
	}

    public function init() 
    {
		$this->includeGMapsJS();
        // JS variable init
        $this->content .= "\t".'<script type="text/javascript">'."\n";
        $this->content .= "\t".'var map;'."\n";
        $this->content .= "\t".'var gmarkers = [];'."\n";
        $this->content .= "\t".'var gicons = [];'."\n";
        $this->content .= "\t".'var clusterer = null;'."\n";
        $this->content .= "\t".'var current_lat = 0;'."\n";
        $this->content .= "\t".'var current_lng = 0;'."\n";
        $this->content .= "\t".'var layer_wikipedia = null;'."\n";
        $this->content .= "\t".'var layer_panoramio = null;'."\n";
        $this->content .= "\t".'var trafficInfo = null;'."\n";
        $this->content .= "\t".'var directions = null;'."\n";
        $this->content .= "\t".'var geocoder = null;'."\n";

        // JS public function to add a marker to the map
        $this->content .= "\t".'function createMarker(lat,lng,html,category,icon) {'."\n";
        $this->content .= "\t\t".'if (icon=="") gicon = new GIcon(G_DEFAULT_ICON);'."\n";
        $this->content .= "\t\t".'else { gicon = new GIcon(G_DEFAULT_ICON,icon); gicon.iconSize = new GSize('. $this->iconWidth.','. $this->iconHeight.'); gicon.shadowSize  = new GSize(0,0); }'."\n";
        $this->content .= "\t\t".'var marker = new GMarker(new GLatLng(lat,lng),gicon);'."\n";
        $this->content .= "\t\t".'marker.mycategory = category;'."\n";

        // Use clusterer optimisation or not
        if ($this->useClusterer==true) {
			// nothing
        } else {
            $this->content .= "\t\t".'map.addOverlay(marker);'."\n";
        }


        $this->content .= "\t\t".'html = \'<div style="float:left;text-align:left;width:'.$this->infoWindowWidth.';">\'+html+\'</div>\''."\n";
        $this->content .= "\t\t".'GEvent.addListener(marker,"click",function() { ';
		
		// Enable the zoom when you click on a marker
		if ($this->enableWindowZoom==true) {
			$this->content .= 'map.setCenter(new GLatLng(lat,lng),'.$this->infoWindowZoom.'); ';
		}
		
		$this->content .= 'marker.openInfoWindowHtml(html); });'."\n"; 
        $this->content .= "\t\t".'gmarkers.push(marker);'."\n";

        // Hide marker by default
        if ($this->defaultHideMarker==true) {
            $this->content .= "\t\t".'marker.hide();'."\n";
        }

        $this->content .= "\t".'}'."\n";
        
        // JS public function to get current Lat & Lng
        $this->content .= "\t".'function getCurrentLat() {'."\n";
        $this->content .= "\t\t".'return current_lat;'."\n";
        $this->content .= "\t".'}'."\n";
        $this->content .= "\t".'function getCurrentLng() {'."\n";
        $this->content .= "\t\t".'return current_lng;'."\n";
        $this->content .= "\t".'}'."\n";


		
		// JS public function to center the gmaps dynamically
        $this->content .= "\t".'function showAddress(address) {'."\n";
        $this->content .= "\t\t".'if (geocoder) {'."\n";
        $this->content .= "\t\t\t".'geocoder.getLatLng('."\n";
        $this->content .= "\t\t\t\t".'address,'."\n";
        $this->content .= "\t\t\t\t".'function(point) {'."\n";
        $this->content .= "\t\t\t\t\t".'if (!point) { alert(address + " not found"); }'."\n";
        $this->content .= "\t\t\t\t\t".'else {'."\n";
        $this->content .= "\t\t\t\t\t\t".'map.setCenter(point, '.$this->zoom.');'."\n";
        $this->content .= "\t\t\t\t\t".'}'."\n";
        $this->content .= "\t\t\t\t".'}'."\n";
        $this->content .= "\t\t\t".');'."\n";
        $this->content .= "\t\t".'}'."\n";
        $this->content .= "\t".'}'."\n";

        $this->content .=  "\t".'</script>'."\n";

        // Google map DIV
        $this->content .= "\t".'<div id="'.$this->googleMapId.'" style="width:'.$this->width.'px;height:'.$this->height.'px"></div>'."\n";
    }

    /**
          * Generate the gmap
          * 
          * @return void
          */

    public function generate() 
    {

        $this->init();

        $this->content .=  "\t".'</script>'."\n";

        // Center of the GMap
		  $geocodeCentre = ($this->latLongCenter) ? $this->latLongCenter : $this->geocoding($this->center);

        if ($geocodeCentre[0]=="200") { // success
            $latlngCentre = $geocodeCentre[2].",".$geocodeCentre[3];
        } else { // Paris
            $latlngCentre = "48.8792,2.34778";
        }

        $this->content .= "\t".'<script type="text/javascript">'."\n";
        $this->content .= "\t".'function load() {'."\n";
        $this->content .= "\t\t".'if (GBrowserIsCompatible()) {'."\n";
        $this->content .= "\t\t\t".'map = new GMap2(document.getElementById("'.$this->googleMapId.'"));'."\n";
        $this->content .= "\t\t\t".'geocoder = new GClientGeocoder();'."\n";

		
		if ($this->enableAutomaticCenterZoom==true) {
			$latlngCentre = $this->centerLat.",".$this->centerLng;
			$this->content .= "\t\t\t".'map.setCenter(new GLatLng('.$latlngCentre.'),'.$this->zoom.');'."\n";
			
			$lenLng = $this->maxLng - $this->minLng;
            $lenLat = $this->maxLat - $this->minLat;
            $this->minLng -= $lenLng * $this->coordCoef;
            $this->maxLng += $lenLng * $this->coordCoef;
            $this->minLat -= $lenLat * $this->coordCoef;
            $this->maxLat += $lenLat * $this->coordCoef;

            $this->content .= "\t\t\t".'var bds = new GLatLngBounds(new GLatLng('.$this->minLat.','.$this->minLng.'),new GLatLng('.$this->maxLat.','.$this->maxLng.'));'."\n";
            $this->content .= "\t\t\t".'map.setZoom(map.getBoundsZoomLevel(bds));'."\n";
		} else {
			$this->content .= "\t\t\t".'map.setCenter(new GLatLng('.$latlngCentre.'),'.$this->zoom.');'."\n";
		}
		
        $this->content .= "\t\t\t".'map.setUIToDefault();'."\n";
        $this->content .= "\t\t\t".'GEvent.addListener(map,"click",function(overlay,latlng) { if (latlng) { current_lat=latlng.lat();current_lng=latlng.lng(); }}) ;'."\n";


        // add all the markers
        $this->content .= $this->contentMarker;
        
        // add the lines
        $this->content .= $this->contentLines;
        
        $this->content .= "\t\t".'}'."\n";
        $this->content .= "\t".'}'."\n";
        $this->content .= "\t".'load();'."\n";
 
        if ($this->useClusterer==true) {
            $this->content .= "\t".'var markerCluster = new MarkerClusterer(map, gmarkers,{gridSize: '.$this->gridSize.', maxZoom: '.$this->maxZoom.'});'."\n";
        }

        $this->content .= "\t".'</script>'."\n";

    }
}