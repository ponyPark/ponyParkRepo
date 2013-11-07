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

    function loadGarages(){

        var request = new XMLHttpRequest();
        var url = 'getParkingLocations.php';
        var data;

        request.open("GET", url, false);
        request.send();
            if (request.readyState === 4) {
                //save the response from server
                //if userLogged.php outputs false, then the signin will display
                //if userLogged.php outputs true, then the favorites, logout, and manage account will display
                var data = JSON.parse(request.responseText);
                var garages = data.ParkingLocations;
                var list = document.getElementById("favList");

                for (var i = 0, j = garages.length; i < j; i++) {
                    var g = garages[i];

                    var parent;
                    var child = $('<ul />');
                    var c1 = $('<li />', {
                        text: g.Address}).appendTo(child);
                    var c2 = $('<li />').appendTo(child);
                    var link = $('<a />',{
                        href: "addFavorites.php?parkingID=" + g.ParkingID,
                        text: "Add To Favorites"}).appendTo(c2);
                        parent = $('<li />', {
                            id: "FavoriteParkingGarageID" + g.ParkingID,
                            text: g.Name});

                    child.appendTo(parent);
                    parent.appendTo(list);
                }
            }

    }
    function changeLink(){

        var request = new XMLHttpRequest();
        var url = 'getFavorites.php';
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {

                var data = JSON.parse(request.responseText);
                var garages = data.Favorites;
                

                for (var i = 0, j = garages.length; i < j; i++) {
                    var g = garages[i];
                    $("#FavoriteParkingGarageID" + g.ParkingID + " ul  li:last-child").text("");
                    var link = $('<a />',{
                        href: "deleteFavorites.php?favoriteID=" + g.FavoriteID,
                        text: "Remove From Favorites"}).appendTo($("#FavoriteParkingGarageID" + g.ParkingID + " ul  li:last-child"));

                }
            }
        }

    }

    userLogged();
    loadGarages();
    changeLink();
}
window.onload = init;
