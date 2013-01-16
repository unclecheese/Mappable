// FIXME avoid global
var marker;

var bounds ;


//console.log('map field loaded');

/*
The following variables are set up by a LiteralField in the LatLongField field, as they names of these fields can of course vary
- latFieldName: latittude field name
- lonFieldName: longitutude field name
- zoomFieldName:  zoom field name
*/



   

   function gmloaded() {
    //console.log('google maps call back');
     initLivequery();
     //initMap();
   }

   // initialise the map

   function initMap() {
    //console.log('init map');

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
      var gm = $('#GoogleMap');
      //console.log(gm);
      var latFieldName = gm.attr('data-latfieldname');
      //console.log("LAT FIELD NAME:"+latFieldName);

      var latField = $('input[name='+gm.attr('data-latfieldname')+']'); //$('input[name="$LatFieldName"]');
      var lonField = $('input[name='+gm.attr('data-lonfieldname')+']'); // $('input[name="$LonFieldName"]');
      var zoomField = $('input[name='+gm.attr('data-zoomfieldname')+']'); // $('input[name="$ZoomFieldName"]');
      var guidePointsAttr = gm.attr('data-GuidePoints');
      console.log(guidePointsAttr);

      var guidePoints = new Array();
      if (typeof guidePointsAttr != "undefined") {
        guidePoints = JSON.parse(guidePointsAttr);
      }
      
      console.log('T1');

      //console.log("latitude field");
      //console.log(latField);
      //console.log("VAL:"+latField.val());
      //console.log('longitude field');
      //console.log(lonField);
      //console.log("VAL:"+lonField.val());


  
       myOptions.center = new google.maps.LatLng(latField.val(), lonField.val());

       if (zoomField.length) {
          myOptions['zoom'] = parseInt(zoomField.val());
          //console.log("ZOOM="+myOptions['zoom']);
          //console.log(zoomField);
       }


       map = new google.maps.Map(document.getElementById("GoogleMap"), myOptions);
       bounds = new google.maps.LatLngBounds ();


       if (guidePoints.length) {
        console.log("GP T1");
        console.log("GP T2");
        console.log(guidePoints);
        var sumlat = 0;
        var sumlon = 0;
        for (var i = guidePoints.length - 1; i >= 0; i--) {
          console.log(i);
          console.log(guidePoints[i]);
          var lat = guidePoints[i].latitude;
          var lon = guidePoints[i].longitude;
          addGuideMarker(lat,lon);
          var latlng = new google.maps.LatLng(lat, lon);
          sumlat = sumlat + parseFloat(lat);
          sumlon = sumlon + parseFloat(lon);

          // extend bounds
          bounds.extend (latlng);
        };

       /*
        var markerPos = marker.getPosition();
        console.log("MARKER POS");
        console.log(markerPos.lat());
    */

        if ((latField.val() == 0) && (lonField.val() == 0)) {
          var nPoints = guidePoints.length;
          console.log("N Points:"+nPoints);
          console.log('sum lat = '+sumlat);
          var newMarkerPos = new google.maps.LatLng(sumlat/nPoints, sumlon/nPoints);
          /*
          marker.setPosition(newMarkerPos);
          console.log("New positino: ");
          console.log(newMarkerPos);
          bounds.extend(newMarkerPos);
          */
        }

        map.fitBounds(bounds);
      }

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
         console.log('set marker');
         setMarker(event.latLng, false);
       });


      
       google.maps.event.addListener(map, "zoom_changed", function(e) {
         if (zoomField.length) {
           zoomField.val(map.getZoom());
         }
       });

      google.maps.event.trigger(map, 'resize');

      map.setZoom( map.getZoom() );

      




     // see http://stackoverflow.com/questions/10197128/google-maps-api-v3-not-rendering-competely-on-tabbed-page-using-twitters-bootst
     //google.maps.event.trigger(map, 'resize');

     $( document ).bind( "pageshow", function( event, data ){
        google.maps.event.trigger(map, 'resize');
      });


     // When the location tab is clicked, resize the map
     $('a[href="#Root_Location"]').click(function() {
        google.maps.event.trigger(map, 'resize');
        var gm = $('#GoogleMap');
        var useMapBounds = gm.attr('data-UseMapBounds');
        console.log(useMapBounds);
        if (useMapBounds) {
          console.log("FITTING MAP TO BOUNDS ON TAB CLICK");
            map.fitBounds(bounds);
        } else {
            console.log("FITTING MAP TO CENTRE OF MARKER");
            map.setCenter(marker.getPosition());
        }
     });

     })(jQuery);

    

    // map.setZoom(map.getZoom());



   }


   // utility functions

   function addGuideMarker(lat,lon) {
    console.log("LAT:"+lat);
    console.log("LON:"+lon);
    var latlng = new google.maps.LatLng(lat, lon);
    var pinColor = "CCCCCC";
    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 34),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 34));
    var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
        new google.maps.Size(40, 37),
        new google.maps.Point(0, 0),
        new google.maps.Point(12, 35));
    var guideMarker = new google.maps.Marker({
      position: latlng,
      title: "Marker",
      icon: pinImage,
      shadow: pinShadow
    });
    guideMarker.setMap(map);

   }

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

      console.log("Set coord by drag");

      var latField = $('input[name="$LatFieldName"]');
      var lonField = $('input[name="$LonFieldName"]');
      var zoomField = $('input[name="$ZoomFieldName"]');

      var lat = event.latLng.lat();
      var lng = event.latLng.lng();
      latField.val(lat);
      lonField.val(lng);

     
       console.log("LATFIELD");
       console.log(latField.val());
       console.log("SHOULD BE "+event.latLng.lng());

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

      //console.log('init live query');

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
         //console.log("ENTRY CLICKED");
         //console.log(t);
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