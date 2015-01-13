<?php

class MapExtension extends DataExtension implements Mappable {

  


  static $db = array(
    'Lat' => 'Decimal(18,15)',
    'Lon' => 'Decimal(18,15)',
    'ZoomLevel' => 'Int'
  );


  static $defaults = array ('Lat' =>0, 'Lon' => 0, 'Zoom' => 4);


  /*
  Add a Location tab containing the map
  */
  public function updateCMSFields( FieldList $fields ) {
    // These fields need removed, as they may have already been created by the form scaffolding
    $fields->removeByName('Lat');
    $fields->removeByName('Lon');
    $fields->removeByName('ZoomLevel');

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

  public function getMappableMapContent() {
    return MapUtil::sanitize($this->owner->renderWith($this->owner->ClassName.'MapInfoWindow'));
  }


  public function getMappableMapPin() {
    return false; //standard pin
  }

  /*
  Check for non zero coordinates, on the assumption that (0,0) will never be the desired coordinates
  */
  public function HasGeo() {
    $result = ($this->owner->Lat != 0) && ($this->owner->Lon != 0);
    if ($this->owner->hasExtension('MapLayerExtension')) {
      if ($this->owner->MapLayers()->count() > 0) {
        $result = true;
      }
    }

    if ($this->owner->hasExtension('PointsOfInterestLayersExtension')) {
      if ($this->owner->PointsOfInterestLayers()->count() > 0) {
        $result = true;
      }
    }

    return $result;

  }


  /*
  Render a map at the provided lat,lon, zoom from the editing functions, 
  */
  public function BasicMap() {
    $map = $this->owner->getRenderableMap();
    // $map->setDelayLoadMapFunction( true );
    $map->setZoom( $this->owner->ZoomLevel );
    $map->setAdditionalCSSClasses( 'fullWidthMap' );
    $map->setShowInlineMapDivStyle( true );
    if (Object::has_extension($this->owner->ClassName, 'MapLayerExtension')) {
      foreach($this->owner->MapLayers() as $layer) {
        $map->addKML($layer->KmlFile()->getAbsoluteURL());
      }
        $map->setEnableAutomaticCenterZoom(true);
    }

    if (Object::has_extension($this->owner->ClassName, 'PointsOfInterestLayerExtension')) {
      foreach($this->owner->PointsOfInterestLayers() as $layer) {
        foreach ($layer->PointsOfInterest() as $poi) {
          $map->addMarkerAsObject($poi);
        }
      }
      $map->setEnableAutomaticCenterZoom(true);
    }

    return $map;
  }

}