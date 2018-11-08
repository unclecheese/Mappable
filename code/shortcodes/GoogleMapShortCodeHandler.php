<?php

class GoogleMapShortCodeHandler
{
    /* Counter used to ensure unique div ids to allow for multiple maps on on page */
    private static $gsv_ctr = 1;

    public static function parse_googlemap($arguments, $caption = null, $parser = null)
    {
        // each of latitude and longitude are required at a bare minimum
        if (!isset($arguments['latitude'])) {
            return '';
        }

        if (!isset($arguments['longitude'])) {
            return '';
        }

        // defaults - can be overriden by using zoom and FIXME in the shortcode
        $defaults = array(
            'Zoom' => 5,
            'MapType' => 'road',
        );

        // ensure JavaScript for the map service is only downloaded once
        $arguments['DownloadJS'] = !MapUtil::get_map_already_rendered();
        MapUtil::set_map_already_rendered(true);

        // convert parameters to CamelCase as per standard template conventions
        $arguments['Latitude'] = $arguments['latitude'];
        $arguments['Longitude'] = $arguments['longitude'];

        // optional parameter caption
        if (isset($arguments['caption'])) {
            $arguments['Caption'] = $arguments['caption'];
        }

        if (isset($arguments['maptype'])) {
            $arguments['MapType'] = $arguments['maptype'];
        }

        // optional parameter zoom
        if (isset($arguments['zoom'])) {
            $arguments['Zoom'] = $arguments['zoom'];
        }

        // the id of the dom element to be used to render the street view
        $arguments['DomID'] = 'google_sc_map_'.self::$gsv_ctr;

        // fullscreen
        $arguments['AllowFullScreen'] = Config::inst()->get('Mappable', 'allow_full_screen');

        // incrememt the counter to ensure a unique id for each map canvas
        ++self::$gsv_ctr;

        // merge defaults and arguments
        $customised = array_merge($defaults, $arguments);

        // include JavaScript to be appended at the end of the page, namely params for map rendering
        //Requirements::javascriptTemplate("mappable/javascript/google/map.google.template.js", $customised);

        //get map view template and render the HTML
        $template = new SSViewer('GoogleMapShortCode');

        //return the template customised with the parmameters
        return $template->process(new ArrayData($customised));
    }

    /**
     * This is only used for testing, otherwise the sequence of tests change the number returned.
     */
    public static function resetCounter()
    {
        self::$gsv_ctr = 1;
    }
}
