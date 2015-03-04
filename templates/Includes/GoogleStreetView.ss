<% include GoogleJavaScript %>
<div id="$DomID" class="streetview googlestreetview"></div>
<% if $Caption %><caption>$Caption</caption><% end_if %>
<script type="text/javascript">
function initialize_{$DomID}() {
  var place_$DomID = new google.maps.LatLng($Latitude,$Longitude);
  var panoramaOptions = {
    position: place_$DomID,
    pov: {
      heading: $Heading,
      pitch: $Pitch
    },
    zoom: $Zoom
  };
  var domNode = document.getElementById('$DomID');
  console.log(domNode);
  var myPano = new google.maps.StreetViewPanorama(
      domNode,
      panoramaOptions);
  myPano.setVisible(true);
}
google.maps.event.addDomListener(window, 'load', initialize_{$DomID});
</script>