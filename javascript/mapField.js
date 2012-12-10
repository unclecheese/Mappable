var marker;

/*
The following variables are set up by a literal field int he map field, as they names of these fields can of course vary
- latFieldName: latittude field name
- lonFieldName: longitutude field name
- zoomFieldName:  zoom field name
*/



   

   function gmloaded() {
     initLivequery();
     //initMap();
   }

   // initialise the map

   function initMap() {
     var myOptions = {
       zoom: 16,
       disableDefaultUI: false,
       mapTypeId: google.maps.MapTypeId.ROADMAP,
       disableDoubleClickZoom: false,
       draggable: true,
       keyboardShortcuts: false,
       scrollwheel: true
     };

     (function($) {
      var latField = $('input[name="$LatFieldName"]');
      var lonField = $('input[name="$LonFieldName"]');
      var zoomField = $('input[name="$ZoomFieldName"]');

  
       myOptions.center = new google.maps.LatLng(latField.val(), lonField.val());

       if (zoomField.length) {
          myOptions['zoom'] = parseInt(zoomField.val());
       }


       map = new google.maps.Map(document.getElementById("GoogleMap"), myOptions);

       if (latField.val() && lonField.val()) {
         marker = null;

         setMarker(myOptions.center, true);
       }





       google.maps.event.addListener(map, "rightclick", function(event) {
         var lat = event.latLng.lat();
         var lng = event.latLng.lng();
         latField.val(lat);
         lonField.val(lng);
         // populate yor box/field with lat, lng
         setMarker(event.latLng, false);
       });


      
       google.maps.event.addListener(map, "zoom_changed", function(e) {
         if ($('input[name="$ZoomFieldName"]').length) {
           $('input[name="$ZoomFieldName"]').val(map.getZoom());
         }
       });

      google.maps.event.trigger(map, 'resize');

      map.setZoom( map.getZoom() );




     // see http://stackoverflow.com/questions/10197128/google-maps-api-v3-not-rendering-competely-on-tabbed-page-using-twitters-bootst
     //google.maps.event.trigger(map, 'resize');

     $( document ).bind( "pageshow", function( event, data ){
        alert('page show');
        google.maps.event.trigger(map, 'resize');
      });


     $('a[href="#Root_Location"]').click(function() {
                google.maps.event.trigger(map, 'resize');

     });

     })(jQuery);

    

    // map.setZoom(map.getZoom());



   }


   // utility functions

   function setMarker(location, recenter) {
     if (marker != null) {
       marker.setPosition(location);
     } else {
       marker = new google.maps.Marker({
         position: location,
         title: "Position",
         draggable: true
       });
       marker.setMap(map);
       google.maps.event.addListener(marker, 'dragend', setCoordByMarker);
     }

     if (recenter) {
       map.setCenter(location)
     }
   }



   function setCoordByMarker(event) {
     (function($) {

      var latField = $('input[name="$LatFieldName"]');
      var lonField = $('input[name="$LonFieldName"]');
      var zoomField = $('input[name="$ZoomFieldName"]');

       latField.val(event.latLng.lat());
       lonField.val(event.latLng.lng());

       if (zoomField.length) {
         zoomField.val(map.getZoom());
       }

       map.setCenter(event.latLng);

     })(jQuery);

   }



   function searchForAddress(address) {
     (function($) {

       var geocoder = new google.maps.Geocoder();
       var elevator = new google.maps.ElevationService();


       if (geocoder) {
         statusMessage("Searching for:" + address);
         geocoder.geocode({
           'address': address
         }, function(results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
             var l = results.length;

             if (l > 0) {
               statusMessage("Places found");
             } else if (l == 0) {
               errorMessage("No places found");
             }

             var html = '<ul class="geocodedSearchResults">';
             //mapSearchResults
             $.each(results, function(index, value) {
               var address = new Array();
               $.each(value.address_components, function(i, v) {
                 address.push(v.long_name);
               });

               html = html + '<li lat="' + value.geometry.location.lat() + '" lon="' + value.geometry.location.lng() + '">' + address + "</li>";
             });

             html = html + "</ul>";

             $('#mapSearchResults').html(html);


             //  setMarker(results[0].geometry.location.lat);
           } else {
             errorMessage("Unable to find any geocoded results");
           }
         });

       }

     })(jQuery);

   }



   // prime livequery events


   function initLivequery() {
     (function($) {

       //triggers
       $('input[name=action_GetCoords]').livequery('click', function(e) {
         // get the data needed to ask coords
         var location = $('#Form_EditForm_Location').val();
         setCoordByAddress(location);
         return false;
       });


       $('#searchLocationButton').livequery('click', function(e) {
         // get the data needed to ask coords
         var location = $('#location_search').val();
         searchForAddress(location);
         return false;
       });

       //geocodedSearchResults
       $('.geocodedSearchResults li').livequery('click', function(e) {
         // get the data needed to ask coords
         var t = $(this);
         console.log("ENTRY CLICKED");
         console.log(t);
         var lat = t.attr("lat");
         var lon = t.attr("lon");
         var address = t.html();
         var latlng = new google.maps.LatLng(lat, lon);
         statusMessage("Setting map to " + address);
         $('.geocodedSearchResults').html('');
         $('#Form_EditForm_Latitude').val(lat);
         $('#Form_EditForm_Longitude').val(lon);

         var latField = $('input[name="$LatFieldName"]');
         var lonField = $('input[name="$LonFieldName"]');
         var zoomField = $('input[name="$ZoomFieldName"]');

         latField.val(lat);
         lonField.val(lon);

         // zoom in to an appropriate level
          map.setZoom(12);

         setMarker(latlng, true);
         return false;
       });


       $('#GoogleMap').livequery(function() {
          initMap();
       });

     })(jQuery);


   }



   (function($) {

     function loadGoogleMapsAPI() {
       var script = document.createElement("script");
       script.type = "text/javascript";
       script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=gmloaded";
       document.body.appendChild(script);
     }



     // deal with document ready - note this only gets called once due to the way silverstripe works, until the CMS is refreshed
     $(document).ready(function() {

       loadGoogleMapsAPI();



     });
   })(jQuery);