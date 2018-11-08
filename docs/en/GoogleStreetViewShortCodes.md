# Google Street View
One can embed Google Street View in a page as an editor using a short code of the following format:
```
[GoogleStreetView latitude="13.811841" longitude="100.527309" heading="162.43" pitch="-10"
caption="Canal south from Pracha Rat 1 Soi 28"]
```
The parameters latitude, longitude, and heading are required.  If not provided the short code will
return an empty string.

For rendering purposes including CSS similar to below in your theme:
```css
.streetview {
	width: 100%;
	height: 500px;
	background: #EEE;
}

/* Ensure map controls are correct aspect ration, and thatFirefox rendering work, 
see http://stackoverflow.com/questions/11340468/street-view-not-working-in-firefox */
.streetview img {
		border: none !important;
		max-width: none !important;
}

.streetviewContainer p.caption {
	text-align: center;
}
```
##Example Rendering
![Example Google Street View Rendering]
(https://raw.githubusercontent.com/gordonbanderson/Mappable/screenshots/screenshots/google-streeview-shortcode-example.png?raw=true
"Example Google Street View Rendering")
