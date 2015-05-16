<% include GoogleJavaScript %>
<div class="googlemapcontainer">
<div id="$DomID" class="map googlemap" data-shortcode-map
data-latitude=$Latitude
data-longitude=$Longitude
data-zoom=$Zoom
data-maptype=$MapType
data-allowfullscreen=$AllowFullScreen
></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>
