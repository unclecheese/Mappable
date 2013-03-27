CLP = $ClustererLibraryPath
<% if DownloadJS %>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;hl=$Lang" type="text/javascript"></script>
<% if UseClusterer %>
  <script src="$ClustererLibraryPath" type="text/javascript"></script>
 <% end_if %>

<!-- common functions -->
<script type="text/javascript" src="/mappable/javascript/google/maputil.js"></script>
<!-- end of common js --> 
<% end_if %>    

<script type="text/javascript">
    var map;
    console.log('Map template JS loaded');
    var gmarkers = [];
    var gicons = [];
    var fluster = null;
    var current_lat = 0;
    var current_lng = 0;
    var layer_wikipedia = null;
    var layer_panoramio = null;
    var trafficInfo = null;
    var directions = null;
    var geocoder = null;
    var infoWindow = new google.maps.InfoWindow({ content: 'test', maxWidth: 400 });


    



</script>

 <div id="$GoogleMapID" <% if ShowInlineMapDivStyle %>style="width:{$Width}px;{$Height}px;"<% end_if %><% if AdditionalCssClasses %>class="$AdditionalCssClasses"<% end_if %>
>
</div>


<script type="text/javascript">
function loadmaps() {
    console.log('mapping service load');
    map = new google.maps.Map(document.getElementById("$GoogleMapID"));
    <% if UseClusterer %>
    fluster = new Fluster2(map);
    <% end_if %>
    geocoder = new google.maps.Geocoder();

    <% if JsonMapStyles %>
    console.log('JSON MAP STYLES');
      var mappableStyles=$JsonMapStyles;
      map.setOptions({styles: mappableStyles});
    <% end_if %>



    <% if EnableAutomaticCenterZoom %>
      map.setCenter(new google.maps.LatLng($LatLngCentre));
      console.log("SET MAP CENTRE TO "+$LatLngCentre);
      var bds = new google.maps.LatLngBounds(new google.maps.LatLng($MinLat,$MinLng),
                new google.maps.LatLng($MaxLat,$MaxLng));
      console.log("BOUNDS");
      console.log(bds);
      map.fitBounds(bds);
     // map.setZoom();

    <% else %>
      map.setCenter(new google.maps.LatLng($LatLngCentre));
      map.setZoom($Zoom);
      console.log("SET MAP CENTRE TO "+$LatLngCentre);
      console.log("T2 SET MAP ZOOM TO $Zoom");


    <% end_if %>

    <% if $MapType %>
       // map.setMapTypeId($MapType);
    <% end_if %>

    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);

    google.maps.event.addListener(map,"click",function(overlay,latlng) { 
        if (latlng) { 
            current_lat=latlng.lat();
            current_lng=latlng.lng(); 
        }
    });

    addAllMarkers($MapMarkers,$UseClusterer,$EnableAutomaticCenterZoom, $DefaultHideMarker);  
    addLines($Lines);
    addKmlFiles($KmlFiles);
    <% if UseClusterer %>fluster.initialize();<% end_if %>
  
}



function loadedGoogleMapsAPI() {
    console.log('callback from google maps');
    loadmaps();
}

<% if DelayLoadMapFunction %>
console.log('delay map loading');
<% else %>
console.log("adding google maps load callback");
google.maps.event.addDomListener(window, 'load', loadedGoogleMapsAPI);
<% end_if %>
</script>