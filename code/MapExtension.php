<?php

class MapExtension extends DataExtension implements Mappable {

  


  static $db = array(
    'Lat' => 'Decimal(18,15)',
    'Lon' => 'Decimal(18,15)',
    'ZoomLevel' => 'Int'
  );


  /*
  Add a Location tab containing the map
  */
  public function updateCMSFields( FieldList $fields ) {
    $fields->addFieldToTab( "Root.Location", new LatLongField( array(
          new TextField( 'Lat', 'Latitude' ),
          new TextField( 'Lon', 'Longitude' ),
          new TextField( 'ZoomLevel', 'Zoom' )
        ),
          array( 'Address' )
          ) 
       );

  }


  public function getMappableLatitude() {
    return $this->owner->Lat;
  }

  public function getMappableLongitude() {
    return $this->owner->Lon;
  }

  public function getMapContent() {
    return MapUtil::sanitize($this->owner->renderWith($this->owner->ClassName.'MapInfoWindow'));
  }
  public function getMapCategory() {
    return 'photo';
  }

  public function getMapPin() {
    return false; //standard pin
  }

  /*
  Check for non zero coordinates, on the assumption that (0,0) will never be the desired coordinates
  */
  public function HasGeo() {
    error_log("MAP EXT: Has geo");
    return ($this->owner->Lat != 0) && ($this->owner->Lon != 0);
  }


  /*
  Render a map at the provided lat,lon, zoom from the editing functions, 
  */
  public function BasicMap() {
    $map = $this->owner->RenderMap();
    // $map->setDelayLoadMapFunction( true );
    $map->setZoom( $this->owner->ZoomLevel );
    $map->setAdditionalCSSClasses( 'fullWidthMap' );
    $map->setShowInlineMapDivStyle( true );
    //$map->addMarkerAsObject($this->owner);

    //$map->addKML('http://assets.tripodtravel.co.nz/cycling/meuang-nont-to-bang-sue-loop.kml');
    return $map;
  }


 

}

?>