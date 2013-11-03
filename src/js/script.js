/*BAM Software JS for PonyPark
October, 2013*/
function init() {

    function userLogged() {
        var request = new XMLHttpRequest();
        var url = 'userLogged.php';
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {
                //save the response from server
                //if userLogged.php outputs false, then the signin will display
                //if userLogged.php outputs true, then the favorites, logout, and manage account will display
                data = request.responseText;
                if (data === "true") {
                    $('#signIn').css('display', 'none');
                    $('#userOptions').css('display', 'block');
                    $('nav ol li:first-child a').attr("href", "index.php");
                    $('nav ol li:first-child a').text("Home");
                }
                if (data === "false") {
                    $('#signIn').css('display', 'block');
                    $('#userOptions').css('display', 'none');
                }

            }
        }


    }
    var map;
    var geocoder;
    var garages;
 
    function drawMap(garages) {
        geocoder = new google.maps.Geocoder();
        var myLatlng = new google.maps.LatLng(32.84200, -96.782460);
        var mapOptions = {
            zoom: 16,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        }
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
        codeLocations(garages, map);
        map.panTo(myLatlng);
    }

    function codeLocations(list, map) {
      for (var i = 0; i < list.length; i++) {
        var geocoder = new google.maps.Geocoder();
        var component = {'postalCode': "75205"};
        var geoOptions = {
          address: list[i].Address,
          componentRestrictions: component
        };
        geocoder.geocode(geoOptions, createGeocodeCallback(list[i], map));
      }
    }

    function createGeocodeCallback(item, map) {
      return function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          addMarker(map, item, results[0].geometry.location);
        } else {
          console.log("Geocode failed " + status);
        }
      }
    }

    function addMarker(map, item, location) {
      var marker = new google.maps.Marker({ map : map, position : location});
      marker.setTitle(item.Name);
      var contentString = '<ol><li><a href="garage.php?garageID=' + item.ParkingID + '">' + item.Name + '</a></ul><li>' + item.Address + '</li><li>' + item.Rating + '</li></ol>';
      var infowindow = new google.maps.InfoWindow( {
        content : contentString,
        size : new google.maps.Size(100, 300)
      });
      new google.maps.event.addListener(marker, "click", function() {
        infowindow.open(map, marker);
      });
    }

    function loadList() {
        var request = new XMLHttpRequest();
        var url = 'getParkingLocations.php';
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {
                //save the response from server
                //if userLogged.php outputs false, then the signin will display
                //if userLogged.php outputs true, then the favorites, logout, and manage account will display
                var data = JSON.parse(request.responseText);
                //var data = {"ParkingLocations":[{"Name":"Binkley Garage","Address":"300 Ownby Drive","Rating":null},{"Name":"Moody Garage","Address":"6004 Bishop Blvd","Rating":null}]};
                var garages = data.ParkingLocations;
                var list = document.getElementById("garageList");

                for (var i = 0, j = garages.length; i < j; i++) {
                    var parent = $('<li />', {
                        style: "border: 4px solid black;"});
                    var anchor = $('<a />', {
                        href: "garage.php?garageID=" + garages[i].ParkingID,
                        text: garages[i].Name}).appendTo(parent);
                    var child = $('<ul />');
                    var c1 = $('<li />', {
                        text: garages[i].Address}).appendTo(child);
                    child.appendTo(parent);
                    parent.appendTo(list);
                }

                drawMap(garages);
                
            }
        
        }
    }

    
    loadList();
    userLogged();
}
window.onload = init;
