<?php

class POIMapPage extends Page {
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('Location');
		return $fields;
	}
}

class POIMapPage_Controller extends Page_Controller {

}
