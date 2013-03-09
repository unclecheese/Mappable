<?php

Object::add_extension("DataObject","MappableData");
Object::add_extension("DataList","MappableDataObjectSet");
//define global path to Components' root folder
if(!defined('MAPPABLE_MODULE_PATH'))
{
	define('MAPPABLE_MODULE_PATH', rtrim(basename(dirname(__FILE__))));
}

// allow geographical format files to be uploaded
File::$allowed_extensions[] = 'gpx' ;
File::$allowed_extensions[] = 'kml' ;

?>