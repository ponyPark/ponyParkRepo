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

    function loadFavorites(){

        var request = new XMLHttpRequest();
        var url = 'getFavorites.php';
        var favorites;

        request.open("GET", url, false);
        request.send();
            if (request.readyState === 4) {
                //save the response from server
                //if userLogged.php outputs false, then the signin will display
                //if userLogged.php outputs true, then the favorites, logout, and manage account will display
                var data = JSON.parse(request.responseText);
                var favorites = data.Favorites;
                console.log(favorites);
                
            }
        var list = document.getElementById("favListDisp");
        request = new XMLHttpRequest();
            if(favorites.length === 0){
                $('#favGLDisp').text("Oops, you don't have any favorites.  Please add some by clicking ")
                var anchor = $('<a />', {
                                href: "editfav.php",
                                text: "here."}).appendTo($('#favGLDisp'));


            }
        for(var i = 0, j = favorites.length; i < j; i++){
            var f = favorites[i];
            var url = 'getParkingInfo.php?parkingID=' + f.ParkingID;
            
            request.open("GET", url, false);
            request.send();
            

                        if (request.readyState === 4) {
                            var data = JSON.parse(request.responseText);
                            var g = data.ParkingInfo;
                            var average_rating = parseInt(g.Average_Rating);
                            var latest_rating = parseInt(g.Latest_Rating);
                            var rating = !average_rating ? latest_rating : average_rating;
                            var rating_message;
                            if (average_rating)
                                rating_message = "Average rating from past 2 hours";
                            else
                                rating_message = "Most recent rating (" + g.Last_Rated + ")";

                            var parent;
                            var child = $('<ul />');
                            var c1 = $('<li />', {
                                text: g.Address}).appendTo(child);
                            var c2;
                            if (rating === 1) {
                                parent = $('<li />');
                                c2 = $('<li />', {
                                    text: rating_message + ': Full'}).appendTo(child);
                            }
                            else if (rating === 2) {
                                parent = $('<li />');
                                c2 = $('<li />', {
                                    text: rating_message + ': Scarce'}).appendTo(child);
                            }
                            else if (rating === 3) {
                                parent = $('<li />');
                                c2 = $('<li />', {
                                    text: rating_message + ': Some'}).appendTo(child);
                            }
                            else if (rating === 4) {
                                parent = $('<li />');
                                c2 = $('<li />', {
                                    text: rating_message + ': Plenty'}).appendTo(child);
                            }
                            else {
                                parent = $('<li />');
                                c2 = $('<li />', {
                                    text: rating_message + ': Empty'}).appendTo(child);
                            }

                            var anchor = $('<a />', {
                                href: "garage.php?garageID=" + g.ParkingID,
                                text: g.Name}).appendTo(parent);

                            child.appendTo(parent);
                            parent.appendTo(list);

                        }
                    



        }
        


    }

    userLogged();
    loadFavorites();
}
window.onload = init;
