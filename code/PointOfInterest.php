<?php 

class PointOfInterest extends DataObject {
	private static $description = 'Represents a point of interest on a map, e.g. railway station';

	private static $belongs_many_many = array('PointsOfInterestLayer' => 'PointsOfInterestLayer');

	private static $db = array('Name' => 'Varchar');

	static $has_one = array(
	    'Icon' => 'Image'
	);

	private static $summary_fields = array('Name');

	function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $fields->addFieldToTab( 'Root.Main', new TextField('Name', 'Name of the item on the map'));
	    $fields->addFieldToTab( 'Root.Main', $uf = new UploadField('Icon', _t('PointsOfInterest.ICON', 'Optional Override Icon')));
	    $uf->setFolderName('mapicons');
	    return $fields;
	}

}