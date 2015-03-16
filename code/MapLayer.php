<?php

class MapLayer extends DataObject {

	static $db = array(
		'Title' => 'Varchar(255)'
	);


	static $has_one = array(
		'KmlFile' => 'File'
	);

	function getCMSFields_forPopup() {
		$fields = new FieldSet();

		$fields->push(new TextField('Title'));
		$fields->push(new FileIFrameField('KmlFile'));

		return $fields;
	}

}
