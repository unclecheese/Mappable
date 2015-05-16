<% include GoogleJavaScript %>
<div class="streetviewcontainer">
<div id="$DomID" class="streetview googlestreetview" data-streetview
data-latitude=$Latitude
data-longitude=$Longitude
data-zoom=$Zoom
data-pitch=$Pitch
data-heading=$Heading
></div>
<% if $Caption %><p class="caption">$Caption</p><% end_if %>
</div>
