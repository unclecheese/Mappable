# Google Map Short Codes
A content editor can embed a Google Map into their content using a short code as follows:
```
[GoogleMap latitude='13.2' longitude='100.4519' caption="Test Google Map" zoom="14" maptype="road"]
```
The parameters latitude and longitude are required.  Zoom defaults to 5 and the map type to road.

Valid map types are
* road
* aerial
* hybrid
* terrain

# Map Type - Road
```
[GoogleMap latitude='13.7402946' longitude='100.5525439' caption="Roads in Central Bangkok"
zoom="14" maptype="road"]
```
![Google Maps - Map Type, Road]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/maptyperoad.png?raw=true
"Google Maps - Map Type, Road")

# Map Type - Aerial
```
[GoogleMap latitude='13.815483' longitude='100.5447213' caption="Bang Sue Train Depot, Thailand"
zoom="20" maptype="aerial"]
```
![Google Maps - Map Type, Aerial]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/maptype-aerial.png?raw=true
"Google Maps - Map Type, Aerial")

# Map Type - Hybrid
```
[GoogleMap latitude='13.8309545' longitude='100.5577219' caption="Junction in Bangkok, Thailand"
zoom="18" maptype="hybrid"]
```
![Google Maps - Map Type, Hybrid]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/maptype-hybrid.png?raw=true
"Google Maps - Map Type, Hybrid")

* Map Type - Terrain
```
[GoogleMap latitude='18.8032393' longitude='98.9166518' caption="Mountains west of Chiang Mai"
zoom="14" maptype="terrain"]
```
![Google Maps - Map Type, Terrain]
(https://github.com/gordonbanderson/Mappable/blob/screenshots/screenshots/maptype-terrain.png?raw=true
"Google Maps - Map Type, Terrain")
