<?php
/**
 * Defines the interface for a mappable DataObject. Implementors of this interface
 * must define the following functions in order to work with the {@link GoogleMapUtil}
 * helper class.
 *
 * @author Uncle Cheese
 * @package mappable
 */
interface Mappable {

	/**
	 * An accessor method for the latitude field.
	 * @example
	 * <code>
	 * 	return $this->Lat;
	 * </code>
	 *
	 * @return string
	 */
	public function getMappableLatitude();
	

	/**
	 * An accessor method for the longitude field.
	 * @example
	 * <code>
	 * 	return $this->Long;
	 * </code>
	 *
	 * @return string
	 */
	public function getMappableLongitude();
	
	
	/**
	 * An accessor method for the path to the marker pin for this point on the map. 
	 * If null or false, use the default Google Maps icon.
	 * @example
	 * <code>
	 * return "mysite/images/map_icon.png";
	 * </code>
	 *
	 * @return string
	 */	
	public function getMappableMapPin();
	
	
	/**
	 * An accessor method that returns the content for the map bubble popup.
	 * It is best to use the {@see ViewableData::renderWith()} method to take advantaging
	 * of templating syntax when rendering the object's content.
	 *
	 * Note: it is critical that the content be sanitized for safe inclusino in the rendered
	 * JavaScript code for the map. {@see GoogleMapsUtil::sanitize()}
	 *
	 * @example
	 * <code>
	 * return GoogleMapsUtil::sanitize($this->renderWith('MapBubble'));
	 * </code>
	 *
	 * @return string
	 */	
	public function getMappableMapContent();

}