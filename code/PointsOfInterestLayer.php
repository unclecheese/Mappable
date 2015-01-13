<?php 
class PointsOfInterestLayer extends DataObject {
	private static $db = array('Name' => 'Varchar');
	
	private static $many_many = array('PointsOfInterest' => 'PointOfInterest');

	static $has_one = array(
	    'DefaultIcon' => 'Image'
	);

	function getCMSFields() {
	    $fields = parent::getCMSFields();
	    $fields->addFieldToTab( 'Root.Main', new TextField('Name', 'Name of this layer'));
	    $fields->addFieldToTab( 'Root.Main', $uf = new UploadField('DefaultIcon', _t('PointsOfInterest.ICON', 'Default Icon')));
	    $uf->setFolderName('mapicons');
	    return $fields;
	}
}