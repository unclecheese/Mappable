<% include GoogleJavaScript %>
<div class="googlemapcontainer">
<div id="$DomID" class="map googlemap"><!-- map is rendered here --></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>
<script type="text/javascript">
var options = {
	latitude: $Latitude,
	longitude: $Longitude,
	zoom: $Zoom,
	maptype: '$MapType',
	domid: '$DomID',
	allowfullscreen: $AllowFullScreen
}
registerShortcodeMap(options);

</script>
