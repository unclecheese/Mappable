#Points of Interest
A point of interest (or POI) denotes a location on a map that is of interest, for example a bridge, a railway station, or a level crossing.  They are grouped together in layers, a PointOfInterest having a many many relationship with PointsOfInterestLayer.  This allows for the use case where "Crewe Railway Station" can appear in a "Railway Stations of Great Britain" layer as well as a "Railway Stations of England" layer.

#Adding Points of Interest to a Page Type
Whilst points of interest layers are available to edit after installing the Mappable module, they need to be added to a DataObject for rendering purposes.  Do this using the standard extensions mechanism in a yml file.  Note that MapExtension is also required.
```
PageWithPointsOfInterest:
  extensions:
    ['MapExtension', 'PointsOfInterestLayerExtension']
```

#Editing
A new model admin tab is available called "Points of Interest".  Here you can add new layers or edit existing ones.

##Adding a New Layer
Add a new layer, in this case "Supermarkets of Bangkok" and save it.
![Adding a new layer](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/001-poi-create-new-layer.png?raw=true "Adding a new layer")


##Adding a new Point of Interest to that Layer
![Adding a new supermarket](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/003-poi-create-new-point-location.png?raw=true "Adding a new supermarket")
![Editing Location](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/004-poi-create-new-point-location.png?raw=true "Editing Location")
![Point of Interest Saved](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/005-poi-saved.png?raw=true "Point of Interest Saved")

#Rendering
The method BasicMap in MapExtension takes into account points of interest when rendering a map.  If you full control of the rendering from within the object containing POIs then use this code as a basis:

```
public function BicycleRideMap() {
	$map = $this->owner->getRenderableMap();
	$map->setZoom( $this->owner->ZoomLevel );
	$map->setAdditionalCSSClasses( 'fullWidthMap' );
	$map->setShowInlineMapDivStyle( true );
	$map->setSize('100%', '500px');

	if (Object::has_extension($this->owner->ClassName, 'MapLayerExtension')) {
	  foreach($this->owner->MapLayers() as $layer) {
	    $map->addKML($layer->KmlFile()->getAbsoluteURL());
	  }
	}

	// add points of interest taking into account the default icon of the layer as an override
	if (Object::has_extension($this->owner->ClassName, 'PointsOfInterestLayerExtension')) {
	  foreach($this->owner->PointsOfInterestLayers() as $layer) {
	    $layericon = $layer->DefaultIcon();
	    if ($layericon->ID === 0) {
	      $layericon = null;
	    }
	    foreach ($layer->PointsOfInterest() as $poi) {
	      if ($poi->MapPinEdited) {
	        if ($poi->MapPinIconID == 0) {
	          $poi->CachedMapPin = $layericon;
	        }
	        $map->addMarkerAsObject($poi);
	      }
	    }
	  }
	  $map->setClusterer( true );
	  $map->setEnableAutomaticCenterZoom(true);
	}

	return $map;
}
```

#Page with Points of Interest Layer
A page type, POIMapPage, is included in the Mappable module.  It is the same as 
a page, with the addition of MapExtension and PointsOfInterestLayerException.  You
will probably have to override the template, POIMapPage.ss in your theme.