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

    function loadList(){
        var request = new XMLHttpRequest();
        var url = 'getRequests.php';
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {
                data = request.responseText;
                data = JSON.parse(data);
                data = data.RequestedGarages;
                var count = data.length;
                for(var i =0; i < count; i++){
                    var status;
                    var cost = data[i].Cost;
                    var comments = data[i].Comments;
                    if(data[i].Status === '0'){
                        status = "Pending";
                    }
                    if(data[i].Status === '1'){
                        status = "Denied";
                    }
                    if(data[i].Status === '2'){
                        status = "Approved";
                    }
                    if(data[i].Cost === null){
                        cost = "None Specified";
                    }
                    else {
                        cost = "$" + cost;
                    }
                    if(data[i].Comments === null){
                        comments = "None Entered";
                    }
                        var parentList = $('#requestStatus');

                        var info = "<li><font size='5'><font class='requestLabels'>Name of Garage:</font> " + data[i].Name + "</font></li>";
                        info += "<ul>";
                        info += "<li><font class='requestLabels'>Address:</font> " + data[i].Address + "</li>";
                        info += "<li><font class='requestLabels'>Cost:</font> " + cost + "</li>";
                        info += "<li><font class='requestLabels'>Levels:</font> " + data[i].NumberOfLevels + "</li>";
                        info += "<li><font class='requestLabels'>Comments:</font> " + comments + "</li>";
                        info += "<li><font class='requestLabels'>Status:</font> <font class='" + status + "'>" + status + "</font></li>";
                        info += "</ul>";
                        info += "<br><br>";

                        parentList.append(info);
                    }
                }

                if(count === 0){
                    $('p.details').text("You have not made any requests.");
                }
            }
        }

    
    loadList();
    userLogged();
}
window.onload = init;
