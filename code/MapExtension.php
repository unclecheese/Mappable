<?php

class MapExtension extends DataExtension implements Mappable {

	/*
	 * Template suffix for rendering MapInfoWindow aka  map bubble
	 */
	private static $map_info_window_suffix = '_MapInfoWindow';

	private static $db = array(
		'Lat' => 'Decimal(18,15)',
		'Lon' => 'Decimal(18,15)',
		'ZoomLevel' => 'Int',
		'MapPinEdited' => 'Boolean'
	);

	static $has_one = array(
		'MapPinIcon' => 'Image'
	);

	static $defaults = array (
		'Lat' =>0,
		'Lon' => 0,
		'Zoom' => 4,
		'MapPinEdited' => false
	);

	/*
	Map editing field
	 */
	private $mapField = null;


	/*
	Add a Location tab containing the map
	*/
	public function updateCMSFields(FieldList $fields) {
		// These fields need removed, as they may have already been created by the form scaffolding
		$fields->removeByName('Lat');
		$fields->removeByName('Lon');
		$fields->removeByName('ZoomLevel');
		$fields->removeByName('MapPinIcon');
		$fields->removeByName('MapPinEdited');

		$fields->addFieldToTab("Root.Location",
			$this->getMapField()
		);

		$fields->addFieldToTab('Root.Location', $uf = new UploadField('MapPinIcon',
			_t('Mappable.MAP_PIN', 'Map Pin Icon.  Leave this blank for default pin to show')));
		$uf->setFolderName('mapicons');
	}


	public function getMappableLatitude() {
		return $this->owner->Lat;
	}

	public function getMappableLongitude() {
		return $this->owner->Lon;
	}


	/**
	 * Renders the map info window for the DataObject.
	 *
	 * Be sure to define a template for that, named by the decorated class suffixed with _MapInfoWindow
	 * e.g. MyPage_MapInfoWindow
	 *
	 * You can change the suffix globally by editing the MapExtension.map_info_window_suffix config val
	 *
	 * @return string
	 */
	public function getMappableMapContent() {
		$defaultTemplate = 'MapInfoWindow';
		$classTemplate =
			SSViewer::get_templates_by_class(
				$this->owner->ClassName,
				Config::inst()->get('MapExtension', 'map_info_window_suffix')
			);

		$template = count($classTemplate) ? $classTemplate : $defaultTemplate;
		return MapUtil::sanitize($this->owner->renderWith($template));
	}


	/*
	If the marker pin is not at position 0,0 mark the pin as edited. This provides the option of
	filtering out (0,0) point which is often irrelevant for plots
	*/
	public function onBeforeWrite() {
		$latzero = ($this->owner->Lat == 0);
		$lonzero = ($this->owner->Lon == 0);
		$latlonzero = $latzero && $lonzero;

		// if both latitude and longitude still default, do not set the map location as edited
		if (!$latlonzero) {
		  $this->owner->MapPinEdited = true;
		}
	}


	/*
	If a user has uploaded a map pin icon display that, otherwise
	*/
	public function getMappableMapPin() {
		$result = false;
		if ($this->owner->MapPinIconID != 0) {
			$mapPin = $this->owner->MapPinIcon();
			$result = $mapPin->getAbsoluteURL();
		} else {
		  // check for a cached map pin already having been provided for the layer
			if ($this->owner->CachedMapPinURL) {
				$result = $this->owner->CachedMapPinURL;
		  	}
		}
		return $result;
	}


	/*
		Check for non zero coordinates, on the assumption that (0,0) will never be the desired coordinates
	*/
	public function HasGeo() {
		$isOrigin = ($this->owner->Lat == 0) && ($this->owner->Lon == 0);
		$result = !$isOrigin;
		if ($this->owner->hasExtension('MapLayerExtension')) {
			if ($this->owner->MapLayers()->count() > 0) {
				$result = true;
			}
		}


		$this->owner->extend('updateHasGeo', $result);
		/**
		 * FIXME - move this to PointsOfInterest module
		if ($this->owner->hasExtension('PointsOfInterestLayerExtension')) {
			if ($this->owner->PointsOfInterestLayers()->count() > 0) {
				$result = true;
			}
		}
		 */

		return $result;
	}


	/*
	Render a map at the provided lat,lon, zoom from the editing functions,
	*/
	public function BasicMap() {
		$map = $this->owner->getRenderableMap()->
			setZoom($this->owner->ZoomLevel)->
			setAdditionalCSSClasses('fullWidthMap')->
			setShowInlineMapDivStyle(true);

		$autozoom = false;

		// add any KML map layers
		if (Object::has_extension($this->owner->ClassName, 'MapLayerExtension')) {
		  foreach($this->owner->MapLayers() as $layer) {
			$map->addKML($layer->KmlFile()->getAbsoluteURL());
			// we have a layer, so turn on autozoom
			$autozoom = true;
		  }
			$map->setEnableAutomaticCenterZoom(true);
		}

		$this->owner->extend('updateBasicMap', $map);

		/**
		FIXME - move to POI module
		if (Object::has_extension($this->owner->ClassName, 'PointsOfInterestLayerExtension')) {
			foreach($this->owner->PointsOfInterestLayers() as $layer) {
				$layericon = $layer->DefaultIcon();
				if ($layericon->ID === 0) {
					$layericon = null;
				}
				foreach ($layer->PointsOfInterest() as $poi) {
					if ($poi->MapPinEdited) {
						if ($poi->MapPinIconID == 0 && $layericon) {
							$poi->CachedMapPinURL = $layericon->getAbsoluteURL();
						}
						$map->addMarkerAsObject($poi);

						// we have a point of interest, so turn on auto zoom
						$autozoom = true;
					}
				}
			}
			$map->setClusterer(true);
		}

		**/

		$map->setEnableAutomaticCenterZoom($autozoom);
		$map->setShowInlineMapDivStyle(true);

		return $map;
	}


	/**
	 * Access the map editing field for the purpose of adding guide points
	 * @return [LatLongField] instance of location editing field
	 */
	public function getMapField() {
		if (!isset($this->mapField)) {
			$this->mapField = new LatLongField(array(
				new TextField('Lat', 'Latitude'),
				new TextField('Lon', 'Longitude'),
				new TextField('ZoomLevel', 'Zoom')
				)
			);
		}
		return $this->mapField;
	}


	/**
	 * Template helper, used to decide whether or not to use compressed assets
	 */
	public function UseCompressedAssets() {
		return Config::inst()->get('Mappable', 'use_compressed_assets');
	}
}
