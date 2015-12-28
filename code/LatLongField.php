<?php

class LatLongField extends FieldGroup {

	protected $latField;

	protected $longField;

	protected $zoomField;

	protected $buttonText;

	private $guidePoints = null;

	private static $ctr = 0;

	/**
	 * @param string[] $buttonText
	 */
	public function __construct($children = array(), $buttonText = null) {
		self::$ctr++;

		if ((sizeof($children) < 2) || (sizeof($children) > 3) ||
			 (!$children[0] instanceof FormField) ||
			 (!$children[1] instanceof FormField)
		) user_error('LatLongField argument 1 must be an array containing at least two FormField '.
				'objects for Lat/Long values, respectively.', E_USER_ERROR);

		parent::__construct($children);

		$this->buttonText = $buttonText ? $buttonText : _t('LatLongField.LOOKUP', 'Search');
		$this->latField = $children[0]->getName();
		$this->longField = $children[1]->getName();

		if (sizeof($children) == 3) {
			$this->zoomField = $children[2]->getName();
		}
		$name = "";
		foreach ($children as $field) {
			$name .= $field->getName();
		}

		// hide the lat long and zoom fields from the interface
		foreach ($this->FieldList() as $fieldToHide) {
			$fieldToHide->addExtraClass('hide');
		}

		$this->name = $name;
	}


	public function FieldHolder($properties = array()) {
		Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-livequery/jquery.livequery.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-metadata/jquery.metadata.js');
		Requirements::javascript(MAPPABLE_MODULE_PATH.'/javascript/mapField.js');

		$attributes = array(
			'class' => 'editableMap',
			'id' => 'GoogleMap',
			'data-LatFieldName' => $this->latField,
			'data-LonFieldName' => $this->longField,
			'data-ZoomFieldName' => $this->zoomField,
			'data-UseMapBounds' => false
	   );

		Requirements::css('mappable/css/mapField.css');

		// check for and if required add guide points
		if (!empty($this->guidePoints)) {
			$latlongps = array();

			foreach ($this->guidePoints as $guidepoint) {
				array_push($latlongps, $guidepoint);
			}

			$guidePointsJSON = json_encode($latlongps);
			// convert the mappable guidepoints to lat lon

			$attributes['data-GuidePoints'] = $guidePointsJSON;

			// we only wish to change the bounds to those of all the points iff
			// the item currently has no location
			$attributes['data-useMapBounds'] = true;
		}
		$content = '<div class="editableMapWrapper">'.$this->create_tag(
			"div",
			$attributes
	   ).'</div>';

		$this->FieldList()->push(new LiteralField('locationEditor', $content));

		$content2 = <<<HTML
<div id="mapSearch">
<input name="location_search" id="location_search" size=80/>
<button class="action" id="searchLocationButton">Search Location Name</button>
	<div id="mapSearchResults">
</div>
</div>
HTML;

		$this->FieldList()->push(new LiteralField('mapSearch', $content2));

		return parent::FieldHolder();
	}

	/*
	Set guidance points for the map being edited.  For example in a photographic set show the map
	position of some other images so that subsequent photo edits do not start with a map centred
	at the origin

	@var newGuidePoints array of points expressed as associative arrays containing keys latitude
						and longitude mapping to geographical locations
	*/
	public function setGuidePoints($newGuidePoints) {
		$this->guidePoints = $newGuidePoints;
	}

	/**
	 * Accessor to guidepoints.  For testing purposes
	 * @return array guidepoints
	 */
	public function getGuidePoints() {
		return $this->guidePoints;
	}

}
