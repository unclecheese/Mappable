<?php 

class PointOfInterest extends DataObject {
	private static $description = 'Represents a point of interest on a map, e.g. railway station';

	private static $belongs_many_many = array('PointsOfInterestLayer' => 'PointsOfInterestLayer');

	private static $db = array('Name' => 'Varchar');

	private static $summary_fields = array('Name');

	function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $fields->addFieldToTab( 'Root.Main', new TextField('Name', 'Name of the item on the map'));
	    return $fields;
	}

}