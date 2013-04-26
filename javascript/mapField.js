// FIXME avoid global
var marker;

var bounds ;


   function gmloaded() {
    //console.log('google maps call back');
     initLivequery();
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
      var latFieldName = gm.attr('data-latfieldname');

      var latField = $('input[name='+gm.attr('data-latfieldname')+']'); 
      var lonField = $('input[name='+gm.attr('data-lonfieldname')+']'); 
      var zoomField = $('input[name='+gm.attr('data-zoomfieldname')+']');
      var guidePointsAttr = gm.attr('data-GuidePoints');

      // if we have emtpy initial values, set them appropriately, otherwise google maps code goes into an infinite tailspin
      if (latField.val() === '') {
        latField.val(0);
      }

      if (lonField.val() === '') {
        lonField.val(0);
      }

      if (zoomField.val() === '') {
        zoomField.val(2);
      }


      var guidePoints = [];
      if (typeof guidePointsAttr != "undefined") {
        guidePoints = JSON.parse(guidePointsAttr);
      }
      
  
       myOptions.center = new google.maps.LatLng(latField.val(), lonField.val());

       if (zoomField.length) {
          myOptions.zoom = parseInt(zoomField.val(),10);
       }


       map = new google.maps.Map(document.getElementById("GoogleMap"), myOptions);
       bounds = new google.maps.LatLngBounds ();

       // guide points are grey marked out pins that are used as contextual hints to the current desired location
       // An example of this would be photographs taken on the same bike ride or walk
       if (guidePoints.length) {
        var sumlat = 0;
        var sumlon = 0;
        for (var i = guidePoints.length - 1; i >= 0; i--) {
          var lat = guidePoints[i].latitude;
          var lon = guidePoints[i].longitude;
          addGuideMarker(lat,lon);
          var latlng = new google.maps.LatLng(lat, lon);
          sumlat = sumlat + parseFloat(lat);
          sumlon = sumlon + parseFloat(lon);

          // extend bounds
          bounds.extend (latlng);
        }


        if ((latField.val() === 0) && (lonField.val() === 0)) {
          var nPoints = guidePoints.length;
          var newMarkerPos = new google.maps.LatLng(sumlat/nPoints, sumlon/nPoints);
        }

        map.fitBounds(bounds);
      }

       if (latField.val() && lonField.val()) {
         marker = null;

         setMarker(myOptions.center, true);
       }


       // when one right clicks, set the red marker flag to that coordinate
       google.maps.event.addListener(map, "rightclick", function(event) {
         var lat = event.latLng.lat();
         var lng = event.latLng.lng();
         latField.val(lat);
         lonField.val(lng);
         setMarker(event.latLng, false);
         statusMessage('Location changed to '+lat+','+lng);
       });


      
       google.maps.event.addListener(map, "zoom_changed", function(e) {
         if (zoomField.length) {
           zoomField.val(map.getZoom());
         }
       });

      google.maps.event.trigger(map, 'resize');
      map.setZoom( map.getZoom() );



     // When any tab is clicked, resize the map
     $('.ui-tabs-anchor').click(function() {
        google.maps.event.trigger(map, 'resize');
        var gm = $('#GoogleMap');
        //console.log(gm);
        var useMapBounds = gm.attr('data-usemapbounds');
        if (useMapBounds) {
            map.fitBounds(bounds);
        } else {
            map.setCenter(marker.getPosition());
        }
     });

     })(jQuery);

   }


   // utility functions

   function addGuideMarker(lat,lon) {
    //console.log("LAT:"+lat);
    //console.log("LON:"+lon);
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
     if (marker !== null) {
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
       map.setCenter(location);
     }
   }



   function setCoordByMarker(event) {
     (function($) {

      var gm = $('#GoogleMap');

      var latField = $('input[name='+gm.attr('data-latfieldname')+']'); 
      var lonField = $('input[name='+gm.attr('data-lonfieldname')+']'); 
      var zoomField = $('input[name='+gm.attr('data-zoomfieldname')+']');
   
      var lat = event.latLng.lat();
      var lng = event.latLng.lng();
      latField.val(lat);
      lonField.val(lng);
      setMarker(event.latLng, true);


      statusMessage('Location changed to '+lat+','+lng);


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
             } else if (l === 0) {
               errorMessage("No places found");
             }

             var html = '<ul class="geocodedSearchResults">';
             //mapSearchResults
             $.each(results, function(index, value) {
               var address = [];
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

        var gm = $('#GoogleMap');


         var latField = $('input[name='+gm.attr('data-latfieldname')+']'); 
        var lonField = $('input[name='+gm.attr('data-lonfieldname')+']'); 
        var zoomField = $('input[name='+gm.attr('data-zoomfieldname')+']');

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