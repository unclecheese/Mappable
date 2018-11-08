<?php

//define global path to Components' root folder
if (!defined('MAPPABLE_MODULE_PATH')) {
    define('MAPPABLE_MODULE_PATH', rtrim(basename(dirname(__FILE__))));
}

ShortcodeParser::get('default')->register('GoogleStreetView', array('GoogleStreetViewShortCodeHandler','parse_googlestreetview'));
ShortcodeParser::get('default')->register('GoogleMap', array('GoogleMapShortCodeHandler','parse_googlemap'));

// Cache for a day
SS_Cache::set_cache_lifetime('mappablegeocoder', 24*60*60);
