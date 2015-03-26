<?php
class PointsOfInterestLayer extends DataObject {
	private static $db = array(
		'Name' => 'Varchar',
		'ShowGuideMarkers' => 'Boolean'
	);

	private static $many_many = array('PointsOfInterest' => 'PointOfInterest');

	static $has_one = array(
	    'DefaultIcon' => 'Image'
	);

	function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $fields->addFieldToTab('Root.Main', new TextField('Name', 'Name of this layer'));
	    $fields->addFieldToTab('Root.Main',
	    	$uf = new UploadField('DefaultIcon',
	    	_t('PointsOfInterest.ICON',
	    	'Default Icon'))
	   	);
	   	$fields->addFieldToTab('Root.Main', new CheckboxField('ShowGuideMarkers',
	    				'Show grey guide markers of others points in this layer'));
	    $uf->setFolderName('mapicons');

	    return $fields;
	}
}
