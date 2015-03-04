<?php

//define global path to Components' root folder
if(!defined('MAPPABLE_MODULE_PATH'))
{
	define('MAPPABLE_MODULE_PATH', rtrim(basename(dirname(__FILE__))));
}

ShortcodeParser::get('default')->register('GoogleStreetView',array('GoogleStreetViewShortCodeHandler','parse_googlestreetview'));

