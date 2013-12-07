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
        var url = 'getAllRequests.php';
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {
                data = request.responseText;
                data = JSON.parse(data);
                data = data.Requested;
                var count = 0;
                for(var i =0; i < data.length; i++){
                    if(data[i].Status === '0'){
                        var cost = data[i].Cost;
                        var comments = data[i].Comments;
                        if(data[i].Cost === null){
                            cost = "None Specified";
                        }
                        else {
                            cost = "$" + cost;
                        }
                        if(data[i].Comments === null){
                            comments = "None Entered";
                        }
                        var parentList = $('#requestList');
                        var info = "<li><font size='5'><font class='requestLabels'>Name of Garage:</font> " + data[i].Name + "</font></li>";
                        info += "<ul>";
                        info += "<li><font class='requestLabels'>Address:</font> " + data[i].Address + "</li>";
                        info += "<li><font class='requestLabels'>Cost:</font> " + cost + "</li>";
                        info += "<li><font class='requestLabels'>Levels:</font> " + data[i].NumberOfLevels + "</li>";
                        info += "<li><font class='requestLabels'>Comments:</font> " + comments + "</li>";
                        info += "</ul>";
                        info += "<span class='submit' id='U" + data[i].RequestID + "'>Approve Request</span>";
                        info += "<span class='submit' style='margin-left:10px;margin-top:10px;margin-bottom:10px;' id='D" + data[i].RequestID + "'>Deny Request</span>";
                        info += "<br><br>";

                        parentList.append(info);
                        var editButton = document.getElementById("U" + data[i].RequestID);
                        editButton.addEventListener("click", modRequest, true);
                        var editButton = document.getElementById("D" + data[i].RequestID);
                        editButton.addEventListener("click", modRequest, true);
                        count++;
                    }
                }

                if(count === 0){
                    $('p.details').text("There are no requests to be reviewed at this time.");
                }
            }
        }



    }

    function modRequest(e){

        var id = e.target.id;
        var idSub = id.substr(1);
        var indicator = id.charAt(0);
        var url;

        var request = new XMLHttpRequest();
        if(indicator === 'U')
        url = 'editRequests.php?status=2&' + 'requestID=' + idSub;
        if(indicator === 'D')
        url = 'editRequests.php?status=1&' + 'requestID=' + idSub;

        request.open("GET", url, false);
        request.send();


        window.location.href = 'admin.php?stat=1';


    }

    
    loadList();
    userLogged();
}
window.onload = init;
