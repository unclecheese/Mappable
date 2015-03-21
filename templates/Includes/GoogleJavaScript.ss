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
<% end_if %>

<script type="text/javascript">
var mappableMaps = [];
</script>
<!-- end of common js -->
<% end_if %>
