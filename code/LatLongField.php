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
	
	public function __construct($children = array(), $addressFields = array(), $buttonText = null) {
		if((sizeof($children) < 2) || (!$children[0] instanceof FormField) || (!$children[1] instanceof FormField)) {
			user_error('LatLongField argument 1 must be an array containing at least two FormField objects for Lat/Long values, respectively.',E_USER_ERROR);
		}
		parent::__construct($children);	
		$this->addressFields = $addressFields;

		$this->buttonText = $buttonText ? $buttonText : _t('LatLongField.LOOKUP','Search');
		$this->latField = $children[0]->getName();
		$this->longField = $children[1]->getName();

		if (sizeof($children) == 3) {
			$this->zoomField = $children[2]->getName();
		}
		$name = "";
		foreach($children as $field) {
			$name .= $field->getName();
		}


		$this->name = $name;
	}
	
	
	public function hasData() {return true;}
	
	public function FieldHolder($properties = array()) {
		Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR.'jquery-livequery/jquery.livequery.js');

		Requirements::javascript(THIRDPARTY_DIR.'/jquery-metadata/jquery.metadata.js');
		Requirements::javascript('mappable/javascript/lat_long_field.js');
				Requirements::css(MAPPABLE_MODULE_PATH.'/css/lat_long_field.css');


		//Requirements::css('mappable/css/lat_long_field.css');
		
		$this->FieldList()->push(new MapField('GoogleMap','GoogleMap'));

		 $content = '<div id="mapSearch">
		 <input name="location_search" id="location_search" size=80/>
    	<button class="action" id="searchLocationButton">Search Location Name</button>
      		<div id="mapSearchResults">
      	</div>
    </div>
    ';

    	$this->FieldList()->push(new LiteralField('mapSearch', $content));

		return parent::FieldHolder();
	}
	
	public function geocode(SS_HTTPRequest $r) {
		if($address = $r->requestVar('address')) {
			if($json = @file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($address))) {
				$response = Convert::json2array($json);
				$location = $response['results'][0]->geometry->location;
				return new SS_HTTPResponse($location->lat.",".$location->lng);
			}
		}
	}
		
}