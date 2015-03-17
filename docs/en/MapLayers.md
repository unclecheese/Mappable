#Map Layers
KML layers can be added through the CMS by adding an extension to the class in question.

```php
<?php
 
// layers are configured in _config.php
 
class PageWithMapAndLayers extends PageWithMap {
 
}
 
class PageWithMapAndLayers_Controller extends DemoPage_Controller {
 
}
?>
```

Add this to extensions.yml

```yml
PageWithMapAndLayers:
	extensions:
		['MapExtension', 'MapLayersExtension']
```

Execute a /dev/build to update your database with the map layers relationship.

When you add a new page of type PageWithMapAndLayers, there is now an extra tab called 'Map Layers'.
Each layer consists of a human readable name and a file attachment, which in this case have to be
KML files.

Templating is the same as before, the $BasicMap method takes account of layers when rendering a map.

##Gotchas
Note you will not be able to see map layers in your dev environment, as the KML file URL needs to be
publicly visible in order that Google's servers can render them.

##Example Rendering
The following screenshot is of an exported KML file from http://www.plotaroute.com/route/43228, a
cycle route along canals avoiding main roads.

![Safe Cycle Route in Bangkok]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/maplayers.png?raw=true 
"Safe Cycle Route in Bangkok")
