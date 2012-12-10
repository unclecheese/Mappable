<?php
/**
 * Map field to point for pois langitude and longitude positioning
 */
class MapField extends DatalessField {
    
    /**
     * @var int $headingLevel The level of the <h1> to <h6> HTML tag. Default: 2
     */
    protected $headingLevel = 2;
    private $divId;
    
    function __construct($name, $title = null, $headingLevel = 2, $allowHTML = false, $form = null) {
    	$this->divId = $name;
        // legacy handling for old parameters: $title, $heading, ...
        // instead of new handling: $name, $title, $heading, ...
        $args = func_get_args();
        if(!isset($args[1]) || is_numeric($args[1])) {
            $title = (isset($args[0])) ? $args[0] : null;
            // Use "HeaderField(title)" as the default field name for a HeaderField; if it's just set to title then we risk
            // causing accidental duplicate-field creation.
            $name = 'MapField' . $title; // this means i18nized fields won't be easily accessible through fieldByName()
            $headingLevel = (isset($args[1])) ? $args[1] : null;
            $allowHTML = (isset($args[2])) ? $args[2] : null;
            $form = (isset($args[3])) ? $args[3] : null;
        } 
        
        if($headingLevel) $this->headingLevel = $headingLevel;
        $this->allowHTML = $allowHTML;
        parent::__construct($name, $title, null, $allowHTML, $form);
    }
    
    function Field($properties = array()) {
        Requirements::javascript('framework/thirdparty/jquery/jquery.js');
        Requirements::javascript('framework/thirdparty/jquery-livequery/jquery.livequery.js');
       // Requirements::javascript('http://maps.google.com/maps/api/js?sensor=false');


       // Requirements::javascript('mappable/javascript/mapField.js');


        


        $attributes = array(
            'class' => 'middleColumn',
            'id' => $this->divId,
            'style' => "width:100%;height:300px;margin:5px 0px 5px 5px;position:relative;"
        );

        Requirements::css('mappable/css/mapField.css');
        
        return '<div class="editableMap">' . $this->createTag(
            "div",
            $attributes
        ) . '</div>';
    }
}
?>