<% include GoogleJavaScript %>
<div class="streetviewcontainer">
<div id="$DomID" class="streetview googlestreetview"></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>
<script type="text/javascript">
var options = {
	latitude: $Latitude,
	longitude: $Longitude,
	zoom: $Zoom,
	pitch: $Pitch,
	heading: $Heading,
	domid: '$DomID'
}
registerStreetView(options);
</script>
