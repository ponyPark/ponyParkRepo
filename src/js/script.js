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
    function drawMap() {
        var myLatlng = new google.maps.LatLng(32.846230, -96.782460);
        var mapOptions = {
            zoom: 12,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        }
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        var marker = new google.maps.Marker({
            position: myLatlng,
            title: "Airline Garage"
        });

        marker.setMap(map);
        map.panTo(myLatlng);
    }

    function loadList() {
        // var request = new XMLHttpRequest();
        // var url = 'getParkingLocations.php';
        // var data;

        // request.open("GET", url, true);
        // request.send();
        // request.onreadystatechange = function (e) {

        //     if (request.readyState === 4) {
        //         //save the response from server
        //         //if userLogged.php outputs false, then the signin will display
        //         //if userLogged.php outputs true, then the favorites, logout, and manage account will display
        //         data = JSON.parse(request.responseText);
                
        //     }
        var data = {"ParkingLocations":[{"Name":"Binkley Garage","Address":"300 Ownby Drive","Rating":null},{"Name":"Moody Garage","Address":"6004 Bishop Blvd","Rating":null}]};
        var garages = data.ParkingLocations;
        console.log(garages);
        var list = document.getElementById("garageList");
        for (var i = 0, j = garages.length; i < j; i++) {
            var parent = $('<li />', {
                style: "border: 4px solid black;"});
            var anchor = $('<a />', {
                href: "garage.php?garageID=",
                text: garages[i].Name}).appendTo(parent);
            var child = $('<ul />');
            var c1 = $('<li />', {
                text: garages[i].Address}).appendTo(child);
            child.appendTo(parent);
            parent.appendTo(list);
        }

    }

    drawMap();
    loadList();
    userLogged();
}
window.onload = init;
