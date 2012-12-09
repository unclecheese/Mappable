<?php

Object::add_extension("DataObject","MappableData");

Object::add_extension("DataObjectSet","MappableDataObjectSet");
//define global path to Components' root folder
if(!defined('MAPPABLE_MODULE_PATH'))
{
	define('MAPPABLE_MODULE_PATH', rtrim(basename(dirname(__FILE__))));
}
?>