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
    var infowindow;
 
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
      var average_rating = parseInt(item.Average_Rating);
      var latest_rating = parseInt(item.Latest_Rating);
      var rating = !average_rating ? latest_rating : average_rating;
      var rating_message;
      if (average_rating)
          rating_message = "Average";
      else
          rating_message = "Most Recent (" + item.Last_Rated + ")";
      var ava;
      var bgc;
      var fgc;
      switch (rating)
      {
        case 1:
            ava = "Full";
            bgc = "#d91818";
            fgc = "#fdeaea";
            break;
        case 2:
            ava = "Scarce";
            bgc = "#d91818";
            fgc = "#fdeaea";
            break;
        case 3:
            ava = "Some";
            bgc = "#f5d10b";
            fgc = "#f7f6df";
            break;
        case 4:
            ava = "Plenty";
            bgc = "#128c16";
            fgc = "#e5fae6";
            break;
        case 5:
            ava = "Empty";
            bgc = "#128c16";
            fgc = "#e5fae6";
            break;
      }

      var contentString = '<font class="map_marker"><a href="garage.php?garageID=' + 
        item.ParkingID + '" class="map_marker_link">' + item.Name + '</a><br>' + item.Address + '<br>' + 
        rating_message + ': ' + ava + "</font>";
      new google.maps.event.addListener(marker, "click", function() {
        if (infowindow)
            infowindow.close();
        infowindow = new InfoBubble({
            content: contentString,
            size: new google.maps.Size(100, 300),
            backgroundColor: fgc,
            borderWidth: 3,
            borderColor: bgc,
            maxWidth: 400,
            minWidth: 200,
            maxHeight: 60
            });
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
                //var data = {"ParkingLocations":[{"Name":"Binkley Garage","Address":"300 Ownby Drive","Rating":"1"},{"Name":"Moody Garage","Address":"6004 Bishop Blvd","Rating":"5"}]};
                var garages = data.ParkingLocations;
                var list = document.getElementById("garageList");

                for (var i = 0, j = garages.length; i < j; i++) {
                    var g = garages[i];
                    var average_rating = parseInt(g.Average_Rating);
                    var latest_rating = parseInt(g.Latest_Rating);
                    var rating = !average_rating ? latest_rating : average_rating;
                    var rating_message;
                    if (average_rating)
                        rating_message = "Average";
                    else
                        rating_message = "Most Recent (" + g.Last_Rated + ")";

                    var parent;
                    var child = $('<ul />');
                    var c1 = $('<li />', {
                        text: g.Address}).appendTo(child);
                    var c2;
                    if (rating === 1) {
                        parent = $('<li />', {
                            style: "border-color: #d91818; background-color: #faefef"});
                        c2 = $('<li />', {
                            text: rating_message + ': Full'}).appendTo(child);
                    }
                    else if (rating === 2) {
                        parent = $('<li />', {
                            style: "border-color: #d91818; background-color: #faefef"});
                        c2 = $('<li />', {
                            text: rating_message + ': Scarce'}).appendTo(child);
                    }
                    else if (rating === 3) {
                        parent = $('<li />', {
                            style: "border-color: #f4cc11; background-color: #f8f8e8"});
                        c2 = $('<li />', {
                            text: rating_message + ': Some'}).appendTo(child);
                    }
                    else if (rating === 4) {
                        parent = $('<li />', {
                            style: "border-color: #128c16; background-color: #e9f8e9"});
                        c2 = $('<li />', {
                            text: rating_message + ': Plenty'}).appendTo(child);
                    }
                    else {
                        parent = $('<li />', {
                            style: "border-color: #128c16; background-color: #e9f8e9"});
                        c2 = $('<li />', {
                            text: rating_message + ': Empty'}).appendTo(child);
                    }

                    var anchor = $('<a />', {
                        href: "garage.php?garageID=" + g.ParkingID,
                        text: g.Name,
                        class: "list_view_link"}).appendTo(parent);

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
