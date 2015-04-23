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

class MapAPI extends ViewableData
{

	 /** GoogleMap key **/
	protected $googleMapKey = '';

	/** GoogleMap ID for the HTML DIV  **/
	protected $googleMapId = 'googlemapapi';

	/** GoogleMap  Direction ID for the HTML DIV **/
	protected $googleMapDirectionId = 'route';

	/* Additional CSS classes to use when rendering the map */
	protected $set_additional_css_classes = '';

	/** Width of the gmap **/
	protected $width = 800;

	/** Height of the gmap **/
	protected $height = 600;

	/** Icon width of the gmarker **/
	protected $iconWidth = 20;

	/** Icon height of the gmarker **/
	protected $iconHeight = 34;

	/* array of lines to be drawn on the map */
	protected $lines = array();

	/* kml file to be rendered */
	protected $kmlFiles = array();

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

	/*
	 Additional CSS classes to render as a class attribute for the div of the
	 map.  Use this if you want more  fine grained control over your map using
	 CSS.  If blank it will be ignored
	 */
	protected $additional_css_classes = '';


	/* Decided whether or not to show the inline map css style on div creation */
	protected $show_inline_map_div_style = true;

	protected $latLongCenter = null;




	/*
		Map styles for google maps, ignored if null
		<pre>
var styles = [
			 {
							 featureType: 'water',
							 elementType: 'all',
							 stylers: [
											 { hue: '#B6C5CF' },
											 { saturation: -54 },
											 { lightness: 1 },
											 { visibility: 'on' }
							 ]
			 },{
							 featureType: 'landscape',
							 elementType: 'all',
							 stylers: [
											 { hue: '#D9D4C8' },
											 { saturation: -32 },
											 { lightness: -8 },
											 { visibility: 'on' }
							 ]
			 },{
							 featureType: 'road',
							 elementType: 'all',
							 stylers: [
											 { hue: '#A69D97' },
											 { saturation: -92 },
											 { lightness: -3 },
											 { visibility: 'on' }
							 ]
			 },{
							 featureType: 'poi',
							 elementType: 'all',
							 stylers: [
											 { hue: '#E7E6DB' },
											 { saturation: -53 },
											 { lightness: 47 },
											 { visibility: 'on' }
							 ]
			 }
];
		</pre>
		*/
	protected $jsonMapStyles = '[]';

	protected $delayLoadMapFunction = false;

	/**
	 * Type of the gmap, can be:
	 *  'road' (roadmap),
	 *  'satellite' (sattelite/aerial photographs)
	 *  'hybrid' (hybrid of road and satellite)
	 *  'terrain' (terrain)
	 *  The JavaScript for the mapping service will convert this into a suitable mapping type
	 */

	protected $mapType = 'road';


	/** Content of the HTML generated **/
	protected $content = '';

	protected $mapService = 'google';

	/** Add the direction button to the infowindow **/
	protected $displayDirectionFields = false;

	/** Hide the marker by default **/
	protected $defaultHideMarker = false;

	/** Extra content (marker, etc...) **/
	//protected $contentMarker = '';

	// a list of markers, markers being associative arrays
	protected $markers = array();

	/** Use clusterer to display a lot of markers on the gmap **/
	protected $useClusterer = false;
	protected $gridSize = 50;
	protected $maxZoom = 17;
	protected $clustererLibraryPath = "/mappable/javascript/google/markerclusterer.js";

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

	/** map center latitude (horizontal), calculated automatically as markers
	are added to the map **/
	protected $centerLat = null;

	/** map center longitude (vertical),  calculated automatically as markers
	are added to the map **/
	protected $centerLng = null;

	/** factor by which to fudge the boundaries so that when we zoom encompass,
	the markers aren't too close to the edge **/
	protected $coordCoef = 0.01;

	/* set this to true to render button to maximize / minimize a map */
	protected $allowFullScreen = null;


	protected static $include_download_javascript = false;


	/**
	 * Class constructor
	 *
	 * @param string  $googleMapKey the googleMapKey
	 *
	 * @return void
	 */

	public function __construct($googleMapKey='') {
		$this->googleMapKey = $googleMapKey;
	}

	/**
	 * Set the key of the gmap
	 *
	 * @param string  $googleMapKey the googleMapKey
	 *
	 * @return void
	 */

	public function setKey($googleMapKey) {
		$this->googleMapKey = $googleMapKey;
		return $this;
	}

	public function setIncludeDownloadJavascript($inclusion) {
		self::$include_download_javascript = $inclusion;
		return $this;
	}


	public function setShowInlineMapDivStyle($new_show_inline_map_div_style) {
		$this->show_inline_map_div_style = $new_show_inline_map_div_style;
		return $this;
	}

	public function setAdditionalCSSClasses($new_additional_css_classes) {
		$this->additional_css_classes = $new_additional_css_classes;
		return $this;
	}


	public function setMapStyle($newStyles) {
		$this->jsonMapStyles = $newStyles;
		return $this;
	}



	public function setDelayLoadMapFunction($newDelay) {
		$this->delayLoadMapFunction = $newDelay;
		return $this;
	}

	/**
	 * Set the useClusterer parameter (optimization to display a lot of marker)
	 *
	 * @param boolean $useClusterer           use cluster or not
	 * @param string  $clusterIcon            the cluster icon
	 * @param int     $maxVisibleMarkers      max visible markers
	 * @param int     $gridSize               grid size
	 * @param int     $minMarkersPerClusterer minMarkersPerClusterer
	 * @param int     $maxLinesPerInfoBox     maxLinesPerInfoBox
	 *
	 * @return void
	 */

	public function setClusterer($useClusterer, $gridSize=50, $maxZoom=17,
			$clustererLibraryPath='/mappable/javascript/google/markerclusterer.js') {
		$this->useClusterer = $useClusterer;
		$this->gridSize = $gridSize;
		$this->maxZoom = $maxZoom;
		$this->clustererLibraryPath = $clustererLibraryPath;
		return $this;
	}

	/**
	 * Set the ID of the default gmap DIV
	 *
	 * @param string  $googleMapId the google div ID
	 *
	 * @return void
	 */

	public function setDivId($googleMapId) {
		$this->googleMapId = $googleMapId;
		return $this;
	}

	/**
	 * Set the ID of the default gmap direction DIV
	 *
	 * @param string  $googleMapDirectionId GoogleMap  Direction ID for the HTML DIV
	 *
	 * @return void
	 */

	public function setDirectionDivId($googleMapDirectionId) {
		$this->googleMapDirectionId = $googleMapDirectionId;
		return $this;
	}

	/**
	 * Set the size of the gmap.  If these values are not provided
	 * then CSS is used instead
	 *
	 * @param int     $width  GoogleMap  width
	 * @param int     $height GoogleMap  height
	 *
	 * @return void
	 */

	public function setSize($width, $height) {
		$this->width = $width;
		$this->height = $height;
		return $this;
	}


	/**
	 * Set the size of the icon markers
	 *
	 * @param int     $iconWidth  GoogleMap  marker icon width
	 * @param int     $iconHeight GoogleMap  marker icon height
	 *
	 * @return void
	 */

	public function setIconSize($iconWidth, $iconHeight) {
		$this->iconWidth = $iconWidth;
		$this->iconHeight = $iconHeight;
		return $this;
	}

	/**
	 * Set the lang of the gmap
	 *
	 * @param string  $lang GoogleMap  lang : fr,en,..
	 *
	 * @return void
	 */

	public function setLang($lang) {
		$this->lang = $lang;
		return $this;
	}

	/**
	 * Set the zoom of the gmap
	 *
	 * @param int     $zoom GoogleMap  zoom.
	 *
	 * @return void
	 */

	public function setZoom($zoom) {
		$this->zoom = $zoom;
		return $this;
	}

	/**
	 * Set the zoom of the infowindow
	 *
	 * @param int     $zoom GoogleMap  zoom.
	 *
	 * @return void
	 */

	public function setInfoWindowZoom($infoWindowZoom) {
		$this->infoWindowZoom = $infoWindowZoom;
		return $this;
	}

	/**
	 * Enable the zoom on the marker when you click on it
	 *
	 * @param int     $zoom GoogleMap  zoom.
	 *
	 * @return void
	 */

	public function setEnableWindowZoom($enableWindowZoom) {
		$this->enableWindowZoom = $enableWindowZoom;
		return $this;
	}

	/**
	 * Enable theautomatic center/zoom at the gmap load
	 *
	 * @param int     $zoom GoogleMap  zoom.
	 *
	 * @return void
	 */

	public function setEnableAutomaticCenterZoom($enableAutomaticCenterZoom) {
		$this->enableAutomaticCenterZoom = $enableAutomaticCenterZoom;
		return $this;
	}

	/**
	 * Set the center of the gmap (an address)
	 *
	 * @param string  $center GoogleMap  center (an address)
	 *
	 * @return void
	 */

	public function setCenter($center) {
		$this->center = $center;
		return $this;
	}

	/**
	 * Set the type of the gmap.  Also takes into account legacy settings
	 *
	 * @param string  $mapType  Can be one of road,satellite,hybrid or terrain. Defaults to road
	 *
	 * @return void
	 */

	public function setMapType($mapType) {
		$this->mapType = $mapType;

		// deal with legacy values for backwards compatbility
		switch ($mapType) {
			case 'google.maps.MapTypeId.SATELLITE':
				$this->mapType = "satellite";
				break;
			case 'google.maps.MapTypeId.SATELLITE':
				$this->mapType = "satellite";
				break;
			case 'google.maps.MapTypeId.SATELLITE':
				$this->mapType = "satellite";
				break;
			default:
				$this->MapType = "road";
				break;
		}

		return $this;
	}

	/*
	Set whether or not to allow the full screen tools
	*/
	public function setAllowFullScreen($allowed) {
		$this->allowFullScreen = $allowed;
		return $this;
	}

	/**
	* Set the center of the gmap
	**/
	public function setLatLongCenter($center) {
		$this->latLongCenter = $center;
		return $this;
	}

	/**
	 * Decide whether or not to show direction controls
	 *
	 * @param boolean $displayDirectionFields display directions or not in the info window
	 *
	 * @return void
	 */

	public function setDisplayDirectionFields($displayDirectionFields) {
		$this->displayDirectionFields = $displayDirectionFields;
		return $this;
	}

	/**
	 * Set the defaultHideMarker
	 *
	 * @param boolean $defaultHideMarker hide all the markers on the map by default
	 *
	 * @return void
	 */

	public function setDefaultHideMarker($defaultHideMarker) {
		$this->defaultHideMarker = $defaultHideMarker;
		return $this;
	}

	/**
	 * Get the google map content
	 *
	 * @return string the google map html code
	 */

	public function getGoogleMap() {
		return $this->content;
	}


	/**
	 * Get URL content using cURL.
	 *
	 * @param string  $url the url
	 *
	 * @return string the html code
	 *
	 * @todo add proxy settings
	 */

	public function getContent($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_URL, $url);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}

	/**
	 * Geocoding an address (address -> lat,lng)
	 *
	 * @param string  $address an address
	 *
	 * @return array array with precision, lat & lng
	 */

	public function geocoding($address) {
		$encodeAddress = urlencode($address);
		$url = "//maps.google.com/maps/geo?q=".$encodeAddress."&output=csv&key=".$this->googleMapKey;

		if (function_exists('curl_init')) {
			$data = $this->getContent($url);
		} else {
			$data = file_get_contents($url);
		}

		$csvSplit = preg_split("/,/", $data);
		$status = $csvSplit[0];

		if (strcmp($status, "200") == 0) {
			/*
			For a successful geocode:
			- $precision = $csvSplit[1
			- $lat = $csvSplit[2]
			- $lng = $csvSplit[3];
			*/
			$return = $csvSplit;
		} else {
			$return = null; // failure to geocode
		}
		return $return;
	}

	/**
	 * Add marker by his coord
	 *
	 * @param string  $lat      lat
	 * @param string  $lng      lngs
	 * @param string  $html     html code display in the info window
	 * @param string  $category marker category
	 * @param string  $icon     an icon url
	 *
	 * @return void
	 */

	public function addMarkerByCoords($lat, $lng, $html='', $category='', $icon='') {
		$m = array(
			'latitude' => $lat,
			'longitude' => $lng,
			'html' => $html,
			'category' => $category,
			'icon' => $icon
		);
		array_push($this->markers, $m);
		return $this;
	}


	/**
	 * Add marker by his address
	 *
	 * @param string  $address  an ddress
	 * @param string  $content  html code display in the info window
	 * @param string  $category marker category
	 * @param string  $icon     an icon url
	 *
	 * @return void
	 */

	public function addMarkerByAddress($address, $content='', $category='', $icon='') {
		$point = $this->geocoding($address);
		if ($point!==null) {
			$this->addMarkerByCoords($point[2], $point[3], $content, $category, $icon);
		} else {
			// throw new Exception('Adress not found : '.$address);
		}
		return $this;
	}

	/**
	 * Add marker by an array of coord
	 *
	 * @param string  $coordtab an array of lat,lng,content
	 * @param string  $category marker category
	 * @param string  $icon     an icon url
	 *
	 * @return void
	 */

	public function addArrayMarkerByCoords($coordtab, $category='', $icon='') {
		foreach ($coordtab as $coord) {
			$this->addMarkerByCoords($coord[0], $coord[1], $coord[2], $category, $icon);
		}
		return $this;
	}


	/**
	 * Adds a {@link ViewableData} object that implements {@link Mappable}
	 * to the map.
	 * @param   $infowindowtemplateparams Optional array of extra parameters to pass to the map info window
	 *
	 * @param ViewableData $obj
	 */
	public function addMarkerAsObject(ViewableData $obj, $infowindowtemplateparams = null) {
		$extensionsImplementMappable = false;
		$extensions = Object::get_extensions(get_class($obj));

		foreach ($extensions as $extension) {
			$class = new ReflectionClass($extension);
			if ($class->implementsInterface('Mappable')) {
				$extensionsImplementMappable = true;
			}

		}
		if ($extensionsImplementMappable ||
			($obj instanceof Mappable) ||
			(Object::has_extension($obj->ClassName, 'MapExtension'))
		) {
			//if(($obj->getMappableLatitude() > 0) || ($obj->getMappableLongitude() > 0)) {
			$cat = $obj->hasMethod('getMappableMapCategory') ? $obj->getMappableMapCategory() : "default";
			if ($infowindowtemplateparams !== null) {
				foreach ($infowindowtemplateparams as $key => $value) {
					$obj->{$key} = $value;
				}
			}
			$this->addMarkerByCoords(
				$obj->getMappableLatitude(),
				$obj->getMappableLongitude(),
				$obj->getMappableMapContent(),
				$cat,
				$obj->getMappableMapPin()
			);
		}

		return $this;
	}


	/**
	 * Draws a line between two {@link ViewableData} objects
	 *
	 * @param ViewableData $one   The first point
	 * @param ViewableData $two   The second point
	 * @param string  $color The hexidecimal color of the line
	 */
	public function connectPoints(ViewableData $one, ViewableData $two, $color = "#FF3300") {
		$this->addLine(
			array($one->getMappableLatitude(), $one->getMappableLongitude()),
			array($two->getMappableLatitude(), $two->getMappableLongitude()),
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
	 * @param string  $coordtab an array of address
	 * @param string  $category marker category
	 * @param string  $icon     an icon url
	 *
	 * @return void
	 */

	public function addArrayMarkerByAddress($coordtab, $category='', $icon='') {
		foreach ($coordtab as $coord) {
			$this->addMarkerByAddress($coord[0], $coord[1], $category, $icon);
		}
		return $this;
	}

	/**
	 * Set a direction between 2 addresss and set a text panel
	 *
	 * @param string  $from    an address
	 * @param string  $to      an address
	 * @param string  $idpanel id of the div panel
	 *
	 * @return void
	 */

	public function addDirection($from, $to, $idpanel='') {
		$this->contentMarker .= 'addDirection("'.$from.'","'.$to.'","'.$idpanel.'");';
	}

	/**
	 * Parse a KML file and add markers to a category
	 *
	 * @param string  $url      url of the kml file compatible with gmap and gearth
	 *
	 * @return void
	 */

	public function addKML($url) {
		array_push($this->kmlFiles, $url);
		return $this;
	}


	/*
	Add a line to the map

	*/
	public function addLine($from = array(), $to = array(), $color = "#FF3300") {
		$line = array(
			'lat1' => $from[0],
			'lon1' => $from[1],
			'lat2' => $to[0],
			'lon2' => $to[1],
			'color' => $color
		);

		array_push($this->lines, $line);
		return $this;
	}


	/*
	For php 5.3
	*/
	function jsonRemoveUnicodeSequences($struct) {
		 return preg_replace("/\\\\u([a-f0-9]{4})/e",
		 					"iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))",
		 					json_encode($struct));
	}


	/**
	 * Generate the gmap
	 *
	 * @return void
	 */

	public function generate() {
		// from http://stackoverflow.com/questions/3586401/cant-decode-json-string-in-php
		$jsonMarkers = null;
		$linesJson = null;
		$kmlJson = null;

		// prior to PHP version 5.4, one needs to use regex
		if (PHP_VERSION_ID < 50400) {
			$jsonMarkers = stripslashes($this->jsonRemoveUnicodeSequences($this->markers));
			$linesJson = stripslashes($this->jsonRemoveUnicodeSequences($this->lines));
			$kmlJson = stripslashes($this->jsonRemoveUnicodeSequences($this->kmlFiles));
		} else {
			$jsonMarkers = stripslashes(json_encode($this->markers,JSON_UNESCAPED_UNICODE));
			$linesJson = stripslashes(json_encode($this->lines,JSON_UNESCAPED_UNICODE));
			$kmlJson = stripslashes(json_encode($this->kmlFiles,JSON_UNESCAPED_UNICODE));
		}



		 // Center of the GMap
		$geocodeCentre = ($this->latLongCenter) ?
							$this->latLongCenter : $this->geocoding($this->center);

		// coordinates for centre depending on which method used
		if ($geocodeCentre[0]=="200") { // success
			$latlngCentre = array('lat'=>$geocodeCentre[2],'lng' => $geocodeCentre[3]);
		} else { // Paris
			$latlngCentre = array('lat'=>48.8792, 'lng' => 2.34778);
		}

		$this->LatLngCentreJSON = stripslashes(json_encode($latlngCentre));

		$lenLng = $this->maxLng - $this->minLng;
		$lenLat = $this->maxLat - $this->minLat;
		$this->minLng -= $lenLng * $this->coordCoef;
		$this->maxLng += $lenLng * $this->coordCoef;
		$this->minLat -= $lenLat * $this->coordCoef;
		$this->maxLat += $lenLat * $this->coordCoef;

		// add the css class mappable as a handle onto the map styling
		$this->additional_css_classes .= ' mappable';

		if (!$this->enableAutomaticCenterZoom) {
			$this->enableAutomaticCenterZoom = 'false';
		}

		if (!$this->useClusterer) {
			$this->useClusterer = 'false';
		}

		if (!$this->defaultHideMarker) {
			$this->defaultHideMarker = 'false';
		}

		if (!$this->MapTypeId) {
			$this->MapTypeId = 'false';
		}

		// initialise full screen as the config value if not already set
		if ($this->allowFullScreen === null) {
			$this->allowFullScreen = Config::inst()->get('Mappable', 'allow_full_screen');
		}

		if (!$this->allowFullScreen) {
			$this->allowFullScreen = 'false';
		}



		$vars = new ArrayData(array(
				'JsonMapStyles' => $this->jsonMapStyles,
				'AdditionalCssClasses' => $this->additional_css_classes,
				'Width' => $this->width,
				'Height' => $this->height,
				'ShowInlineMapDivStyle' => $this->show_inline_map_div_style,
				'InfoWindowZoom' => $this->infoWindowZoom,
				'EnableWindowZoom' => $this->enableWindowZoom,
				'MapMarkers' => $jsonMarkers,
				'DelayLoadMapFunction' => $this->delayLoadMapFunction,
				'DefaultHideMarker' => $this->defaultHideMarker,
				'LatLngCentre' => $this->LatLngCentreJSON,
				'EnableAutomaticCenterZoom' => $this->enableAutomaticCenterZoom,
				'Zoom' => $this->zoom,
				'MaxZoom' => $this->maxZoom,
				'GridSize' => $this->gridSize,
				'MapType' => $this->mapType,
				'GoogleMapID' => $this->googleMapId,
				'Lang'=>$this->lang,
				'UseClusterer'=>$this->useClusterer,
				'DownloadJS' => !(self::$include_download_javascript),
				'ClustererLibraryPath' => $this->clustererLibraryPath,
				'ClustererMaxZoom' => $this->maxZoom,
				'ClustererGridSize' => $this->gridSize,
				'Lines' => $linesJson,
				'KmlFiles' => $kmlJson,
				'AllowFullScreen' => $this->allowFullScreen,
				'UseCompressedAssets' => Config::inst()->get('Mappable', 'use_compressed_assets')
			)
		);

		// JavaScript required to prime the map
		$javascript = $this->processTemplateJS('Map', $vars);
		$vars->setField('JavaScript', $javascript);

		// HTML component of the map
		$this->content = $this->processTemplateHTML('Map', $vars);
	}

	function processTemplateJS($templateName, $templateVariables = null) {
		if (!$templateVariables) {
			$templateVariables = new ArrayList();
		}
		$mappingService = Config::inst()->get('Mappable', 'mapping_service');
		$result = $templateVariables->renderWith($templateName.$mappingService.'JS');
		return $result;
	}

	function processTemplateHTML($templateName, $templateVariables = null ) {
		if (!$templateVariables) {
			$templateVariables = new ArrayList();
		}
		$mappingService = Config::inst()->get('Mappable', 'mapping_service');
		$result = $templateVariables->renderWith($templateName.$mappingService.'HTML');
		return $result;
	}
}
