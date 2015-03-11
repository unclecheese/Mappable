#Adding Lines to Maps
A line can be added to a map with the following API call:

```
    $map->addLine( $point1, $point2, $colorHexCode );
```

Each point is an array whose 0th element is the latitude and 1st element is the longitude.  The third parameter is optional and represents the color of the line in standard CSS hex code colors (RGB).

An example method to draw a multicolored triangle on a map is as follows:

```
/*
  Render a triangle around the provided lat,lon, zoom from the editing functions,
  */
  public function MapWithLines() {
    $map = $this->owner->getRenderableMap();
    $map->setZoom( $this->ZoomLevel );
    $map->setAdditionalCSSClasses( 'fullWidthMap' );
    $map->setShowInlineMapDivStyle( true );
 
    $scale = 0.3;
 
    // draw a triangle
    $point1 = array(
      $this->Lat - 0.5*$scale, $this->Lon
    );
    $point2 = array(
      $this->Lat + 0.5*$scale, $this->Lon-0.7*$scale
    );
 
    $point3 = array(
      $this->Lat + 0.5*$scale, $this->Lon+0.7*$scale
    );
 
    $map->addLine( $point1, $point2 );
    $map->addLine( $point2, $point3, '#000077' );
    $map->addLine( $point3, $point1, '#007700' );
 
    return $map;
  }
```



  Instead of calling $BasicMap call $MapWithLines instead from the template.

  See http://demo.weboftalent.asia/mappable/map-with-lines/ for a working demo.
