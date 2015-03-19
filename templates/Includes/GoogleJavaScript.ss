<% if DownloadJS %>
<% if DelayLoadMapFunction %>
<% else %>
<script src="//maps.google.com/maps/api/js?sensor=false&amp;hl=$Lang" type="text/javascript"></script>
<% if $UseCompressedAssets %>
<script type="text/javascript" src="/mappable/javascript/google/mappablegoogle.min.js"></script>
<% else %>
<script type="text/javascript" src="/mappable/javascript/google/FullScreenControl.js"></script>
<script type="text/javascript" src="/mappable/javascript/google/markerclusterer.js"></script>
<script type="text/javascript" src="/mappable/javascript/google/maputil.js"></script>
<% end_if %>

<script type="text/javascript">google.maps.event.addDomListener(window, 'load', loadedGoogleMapsAPI);</script>
<% end_if %>
<script type="text/javascript">
// map details are stored here and used to invoke maps in the loadedGoogleMapsAPI function
var mappableMaps = [];
</script>
<!-- end of common js -->
<% end_if %>
