<% include GoogleJavaScript %>
<div class="googlemapcontainer">
Test
<div id="$DomID" class="map googlemap"><!-- map is rendered here --></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>

<script type="text/javascript">
  function initialize() {
    var mapOptions = {
      center: { lat: $Latitude, lng: $Longitude},
      zoom: $Zoom,
      mapTypeId: google.maps.MapTypeId.{$MapType}
    };
    var map = new google.maps.Map(document.getElementById('$DomID'),
        mapOptions);
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>