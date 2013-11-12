#Adding a Map to a DataObject

Using the standard method of adding extensions in SilverStripe 3.1, add an extension called 'MapExtension' to relevant DataObjects.

```
---
name: weboftalent-example-map-extensions
---
PageWithMap:
  extensions:
    ['MapExtension']

```

This adds Latitude, Longitude and Zoom fields to the DataObject in question, here 'PageWithMap'.  In addition, the admin interface for PageWithMap now has a location tab.  Location can be changed in 3 ways
* Use the geocoder and search for a place name
* Drag the map pin
* Right click
The zoom level set by the content editor is also saved.
To render a map in the template, simply called $BasicMap

```
<h1>$Title</h1>
$Content
 
$BasicMap
```

For an example of this, see http://demo.weboftalent.asia/mappable/quick-map-adding-a-map-to-a-dataobject/


#Multiple Maps on the Same Page
Multiple maps can be added to a page.  One option is to associate maps with a DataObject whose relationship to the parent page is 'has many', as in this example of contact page addresses.
##Example
Often a company will have more than one office location that they wish to display, this is a an example of that use case.  It would probably need expanding in order to show the likes of email address and telephone number,  left as an exercise for the reader.

Firstly, create a parent container page called ContactPage, this has many locations of type ContactPageAddress.
```
<?php
class ContactPage extends DemoPage {
 
  static $has_many = array(
    'Locations' => 'ContactPageAddress'
  );
 
  function getCMSFields() {
    $fields = parent::getCMSFields();
 
    $gridConfig = GridFieldConfig_RelationEditor::create();
    $gridConfig->getComponentByType( 'GridFieldAddExistingAutocompleter' )->setSearchFields( array( 'PostalAddress' ) );
    $gridConfig->getComponentByType( 'GridFieldPaginator' )->setItemsPerPage( 100 );
    $gridField = new GridField( "Locations", "List of Addresses:", $this->Locations(), $gridConfig );
    $fields->addFieldToTab( "Root.Addresses", $gridField );
 
    return $fields;
  }
}
 
class ContactPage_Controller extends Page_Controller {
 
 
}
 
?>
```

The latter contains the actual map for each location, configured as above using extensions.yml

```
<?php
class ContactPageAddress extends DataObject {
	static $db = array(
		'PostalAddress' => 'Text'
	);
 
	static $has_one = array( 'ContactPage' => 'ContactPage' );
 
 
	public static $summary_fields = array(
		'PostalAddress' => 'PostalAddress'
	);
 
 
	function getCMSFields() {
		$fields = new FieldList();
		$fields->push( new TabSet( "Root", $mainTab = new Tab( "Main" ) ) );
		$mainTab->setTitle( _t( 'SiteTree.TABMAIN', "Main" ) );
		$fields->addFieldToTab( "Root.Main", new TextField( 'PostalAddress' ) );
 
		$this->extend( 'updateCMSFields', $fields );
 
		return $fields;
	}
}
?>
```

The template simply loops through the contact page addresses, rendering a map.

```
<h1>$Title</h1>
$BriefDescription
 
<h2>Addresses</h2>
 
<% loop Locations %>
<h3>$PostalAddress</h3>
$BasicMap
<% end_loop %>
 
$Content
```

See http://demo.weboftalent.asia/mappable/multiple-maps-on-the-same-page/ for a working demo.


#Map Layers
KML layers can be added through the CMS using only a line of configuration.

```
<?php
 
// layers are configured in _config.php
 
class PageWithMapAndLayers extends PageWithMap {
 
}
 
class PageWithMapAndLayers_Controller extends DemoPage_Controller {
 
}
?>
```

Add this to extensions.yml

```
PageWithMapAndLayers:
  extensions:
    ['MapExtension', 'MapLayersExtension']
```

Do a /dev/build to update your database with the map layers relationship.

When you add a new page of type PageWithMapAndLayers, there is now an extra tab called 'Map Layers'.  Each layer consists of a human readable name and a file attachment, which in this case have to be KML files.
Templating is the same as before, the $BasicMap method takes account of layers when rendering a map.

##Gotchas
Note that by default, one cannot upload KML files to the assets area, as .gpx and .kml files are blocked by default.  Add them to /assets/.htacces.  I've create a pull request to fix this, https://github.com/silverstripe/silverstripe-installer/pull/56

Also note you will not be able to see map layers in your dev environment, as the KML file URL needs to be publicly visible.

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

For clustered and unclustered examples, see http://demo.weboftalent.asia/mappable/map-from-a-datalist-unclustered/ and http://demo.weboftalent.asia/mappable/map-with-datalist-clustered-markers/ respectively.


#TODO
* Render different markers or icons as map pointers
* Add map marker sets, where a set of markers e.g. of underground stations can be added to multiple maps on your website.
