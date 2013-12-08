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
                $('#favGLDisp').text("Oops, you don't have any favorites.  Please add some by clicking ");
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
                                rating_message = "Average";
                            else
                                rating_message = "Most Recent (" + g.Last_Rated + ")";

                            if (rating === 1)
                                rating = "Full";
                            else if (rating === 2)
                                rating = "Scarce";
                            else if (rating === 3)
                                rating = "Some";
                            else if (rating === 4)
                                rating = "Plenty";
                            else if (rating === 5)
                                rating = "Empty";
                            
                            var info = "<a href='garage.php?garageID=" + g.ParkingID + "' class='favoriteLink'>" + g.Name + "</a>";
                            info += "<ul>";
                            info += "<li><font class='requestLabels'>Address:</font> " + g.Address + "</li>";
                            info += "<li><font class='requestLabels'>" + rating_message + ":</font> " + rating + "</li>";
                            info += "</ul>";
                            info += "<br><br>";

                            list.innerHTML += info;

                        }
                    



        }
        


    }

    userLogged();
    loadFavorites();
}
window.onload = init;
