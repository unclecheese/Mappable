<% if DownloadJS %>
<% if DelayLoadMapFunction %>
<% else %>
<script type="text/javascript">
function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?' +
      '&sensor=false&callback=loadedGoogleMapsAPI&hl=en';
  document.body.appendChild(script);
}
window.addEventListener ?
        window.addEventListener("load",loadScript,false) :
window.attachEvent && window.attachEvent("onload",loadScript);
</script>
<% if $UseCompressedAssets %>
<% require javascript("mappable/javascript/google/mappablegoogle.min.js") %>
<% else %>
<script type="text/javascript">
var mappableMaps = [];
var shortcodeMaps = [];
var shortcodeStreetview = [];
</script>
<% require javascript("mappable/javascript/google/FullScreenControl.js") %>
<% require javascript("mappable/javascript/google/markerclusterer.js") %>
<% require javascript("mappable/javascript/google/maputil.js") %>
<% end_if %>
<% end_if %>
<!-- end of common js -->
<% end_if %>
