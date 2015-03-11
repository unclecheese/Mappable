#Mapping a DataList

The principle difference from a simple map is that the renderable map is obtained from the DataList itself.  The objects in the DataList must implement the mappable interface, or use the extension called MapExtension.  This requirement means that all the objects will have the necessary information to be rendered as a marker on a map.

The following example is the code that renders the map on this page.  Note that the clusterer on this page is only invoked if the checkbox 'Cluster Example Dataset' is set to true, in this case it is not.

```
public function MapWithDataList() {
    $flickrPhotos = DataList::create( 'FlickrPhoto' )->where( 'Lat != 0 AND Lon !=0' );
    if ( $flickrPhotos->count() == 0 ) {
      return ''; // don't render a map
    }
 
    $map = $flickrPhotos->getRenderableMap();
    $map->setZoom( $this->ZoomLevel );
    $map->setAdditionalCSSClasses( 'fullWidthMap' );
    $map->setShowInlineMapDivStyle( true );
    if ( $this->ClusterExampleDataset ) {
      $map->setClusterer( true );
    }
 
    return $map;
  }
 ```

The map is positioned so that it shows all of the points automatically.  Also note that the host page of the map does not require to implement mappable or even have a location attached to it, as the map is rendered entirely from the DataList, in this case $flickrPhotos.

For clustered and unclustered examples, see:
http://demo.weboftalent.asia/mappable/map-from-a-datalist-unclustered/
![Non Clustered DataList](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/datalist-unclustered.png?raw=true "Non Clustered DataList")
http://demo.weboftalent.asia/mappable/map-with-datalist-clustered-markers/
![Clustered DataList](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/datalist-clustered.png?raw=true "Clustered DataList")


