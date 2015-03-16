#Adding a Map to a DataObject
##Add Extension
Using the standard method of adding extensions in SilverStripe 3.1, add an extension called 'MapExtension' to relevant DataObjects.

```
---
name: weboftalent-example-map-extensions
---
PageWithMap:
  extensions:
    ['MapExtension']

```
##Editing
Utilising the extensions adds Latitude, Longitude and Zoom fields to the DataObject in question, in the example above 'PageWithMap'.  In addition, the admin interface for PageWithMap now has a location tab.  Location can be changed in 3 ways
* Use the geocoder and search for a place name ![Map Editing - Searching for a Place Name](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/mapedit-search.png?raw=true "Map Editing - Searching for a Place Name")
* Drag the map pin
* Right click ![Map Editing - Right Clicking on a Map](https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/mapedit-rightclick.png?raw=true "Map Editing - Right Clicking on a Map")

The zoom level set by the content editor is also saved.

##Templates
To render a map in the template, simply called $BasicMap

```
<h1>$Title</h1>
$Content
$BasicMap
```

For an example of this, see http://demo.weboftalent.asia/mappable/quick-map-adding-a-map-to-a-dataobject/

##Custom Popup
When clicking on the pin a popup with the name of the pin will occur.

If you want to change the information displayed there you have to setup a template named by the decorated DataObject suffixed with `_MapInfoWindow`, e.g. `MyPage_MapInfoWindow`.
