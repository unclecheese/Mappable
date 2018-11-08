<% include GoogleJavaScript %>
<div id="$GoogleMapID"<% if $GoogleMapKey %> data-google-map-key="$GoogleMapKey"<% end_if %><% if $GoogleMapLang %> data-google-map-lang="$GoogleMapLang" <% end_if %><% if ShowInlineMapDivStyle %> style="width:{$Width}; height: {$Height};"
<% end_if %><% if AdditionalCssClasses %> class="$AdditionalCssClasses"<% end_if %>
data-map
data-centre='$LatLngCentre'
data-zoom=$Zoom
data-maptype='$MapType'
data-allowfullscreen='$AllowFullScreen'
data-clusterergridsize=$ClustererGridSize,
data-clusterermaxzoom=$ClustererMaxZoom,
data-enableautocentrezoom=$EnableAutomaticCenterZoom
data-enablewindowzoom=$EnableWindowZoom
data-infowindowzoom=$InfoWindowZoom
data-mapmarkers='$MapMarkers'
data-defaulthidemarker=$DefaultHideMarker
data-lines='$Lines'
data-kmlfiles='$KmlFiles'
data-mapstyles='$JsonMapStyles'
data-useclusterer=$UseClusterer
>
</div>
