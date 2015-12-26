<?php

class MapLayer extends DataObject {

	static $db = array(
		'Title' => 'Varchar(255)'
	);


	static $has_one = array(
		'KmlFile' => 'File'
	);

}
