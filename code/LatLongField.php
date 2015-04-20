<?php

class LatLongField extends FieldGroup {

	static $allowed_actions = array (
		'geocode'
	);

	protected $addressFields = array();

	protected $latField;

	protected $longField;

	protected $zoomField;

	protected $buttonText;

	private static $ctr = 0;

	public function __construct($children = array(), $addressFields = array(), $buttonText = null) {
		$id = spl_object_hash($this);

		self::$ctr++;
		if (self::$ctr == 2) {
			//asdfsda;
		}

		if ((sizeof($children) < 2) ||
			 (!$children[0] instanceof FormField) ||
			 (!$children[1] instanceof FormField)
		) {
			user_error('LatLongField argument 1 must be an array containing at least two FormField '.
				'objects for Lat/Long values, respectively.', E_USER_ERROR);
		}
		parent::__construct($children);
		$this->addressFields = $addressFields;

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


	public function hasData() {
		return true;
	}


	public function FieldHolder($properties = array()) {
		Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-livequery/jquery.livequery.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-metadata/jquery.metadata.js');
		//Requirements::javascript(MAPPABLE_MODULE_PATH.'/javascript/mapField.js');

		$js = '
		<script type="text/javascript">
var latFieldName = "'.$this->latField.'";
var lonFieldName = "'.$this->longField.'";
var zoomFieldName = "'.$this->zoomField.'";
		</script>
	';

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
        $guidePointsJSON = '';
        if (isset($this->guidePoints)) {
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
        $content = '<div class="editableMapWrapper">' . $this->createTag(
            "div",
            $attributes
       ) . '</div>';

        $this->FieldList()->push(new LiteralField('locationEditor', $content));

		$content2 = '<div id="mapSearch">
		 <input name="location_search" id="location_search" size=80/>
    	<button class="action" id="searchLocationButton">Search Location Name</button>
      		<div id="mapSearchResults">
      	</div>
	    </div>
	    ';

		$this->FieldList()->push(new LiteralField('mapSearch', $content2));

		return parent::FieldHolder();
	}


	/*
	Perform place name search as a means of navigation when editing locations
	*/
	public function geocode(SS_HTTPRequest $r) {
		if ($address = $r->requestVar('address')) {
			if ($json = @file_get_contents(
				"http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".
				urlencode($address))) {
				$response = Convert::json2array($json);
				$location = $response['results'][0]->geometry->location;
				return new SS_HTTPResponse($location->lat.",".$location->lng);
			}
		}
	}


	/*
	Set guidance points for the map being edited.  For example in a photographic set show the map
	position of some other images so that subsequent photo edits do not start with a map centred
	on the horizon
	*/
	public function setGuidePoints($newGuidePoints) {
		$this->guidePoints = $newGuidePoints;
	}

}
