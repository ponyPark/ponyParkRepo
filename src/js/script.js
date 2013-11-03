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
                    $('nav ol li:first-child a').text("Availability");
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
      var rating = parseInt(item.Rating);
      console.log(rating);
      var ava;
      var bgc;
      switch (rating)
      {
        case 1:
            ava = "Full";
            bgc = "#FF8C8C";
            break;
        case 2:
            ava = "Scarce";
            bgc = "#FF8C8C";
            break;
        case 3:
            ava = "Some";
            bgc = "#FFF78C";
            break;
        case 4:
            ava = "Plenty";
            bgc = "#A8FFAB";
            break;
        case 5:
            ava = "Empty";
            bgc = "#A8FFAB";
            break;
      }

      var contentString = '<ol><li><a href="garage.php?garageID=' + item.ParkingID + '">' + item.Name + '</a></ul><li>' + item.Address + '</li><li>Availability: ' + ava + '</li></ol>';
      var infowindow = new InfoBubble( {
        content : contentString,
        size : new google.maps.Size(100, 300),
        backgroundColor: bgc,
        maxWidth: 300,
        minWidth: 50,
        maxHeight: 80
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
                //var data = JSON.parse(request.responseText);
                var data = {"ParkingLocations":[{"Name":"Binkley Garage","Address":"300 Ownby Drive","Rating":"1"},{"Name":"Moody Garage","Address":"6004 Bishop Blvd","Rating":"5"}]};
                var garages = data.ParkingLocations;
                var list = document.getElementById("garageList");

                for (var i = 0, j = garages.length; i < j; i++) {
                    var g = garages[i];
                    var rating = parseInt(g.Rating);

                    var parent;
                    var child = $('<ul />');
                    var c1 = $('<li />', {
                        text: g.Address}).appendTo(child);
                    var c2;
                    if (rating === 1) {
                        parent = $('<li />', {
                            style: "border: 4px solid red;"});
                        c2 = $('<li />', {
                            text: 'Availability: Full'}).appendTo(child);
                    }
                    else if (rating === 2) {
                        parent = $('<li />', {
                            style: "border: 4px solid red;"});
                        c2 = $('<li />', {
                            text: 'Availability: Scarce'}).appendTo(child);
                    }
                    else if (rating === 3) {
                        parent = $('<li />', {
                            style: "border: 4px solid yellow;"});
                        c2 = $('<li />', {
                            text: 'Availability: Some'}).appendTo(child);
                    }
                    else if (rating === 4) {
                        parent = $('<li />', {
                            style: "border: 4px solid green;"});
                        c2 = $('<li />', {
                            text: 'Availability: Plenty'}).appendTo(child);
                    }
                    else {
                        parent = $('<li />', {
                            style: "border: 4px solid green;"});
                        c2 = $('<li />', {
                            text: 'Availability: Empty'}).appendTo(child);
                    }

                    var anchor = $('<a />', {
                        href: "garage.php?garageID=" + g.ParkingID,
                        text: g.Name}).appendTo(parent);

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
