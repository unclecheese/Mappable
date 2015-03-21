<% if DownloadJS %>
<% if DelayLoadMapFunction %>
<% else %>
<% require javascript("//maps.google.com/maps/api/js?sensor=false&amp;hl=$Lang") %>

<% if $UseCompressedAssets %>
<% require javascript("mappable/javascript/google/mappablegoogle.min.js") %>
<% else %>
<% require javascript("mappable/javascript/google/FullScreenControl.js") %>
<% require javascript("mappable/javascript/google/markerclusterer.js") %>
<% require javascript("mappable/javascript/google/maputil.js") %>
<% end_if %>

<script type="text/javascript">google.maps.event.addDomListener(window, 'load', loadedGoogleMapsAPI);</script>
<% end_if %>
<script type="text/javascript">
// map details are stored here and used to invoke maps in the loadedGoogleMapsAPI function
var mappableMaps = [];
</script>
<!-- end of common js -->
<% end_if %>
