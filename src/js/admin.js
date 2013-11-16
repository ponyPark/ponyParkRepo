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
                        var parentList = $('#requestList');
                        var child1 = $('<li />', {text: "Name of Garage: " + data[i].Name});
                        var child1child1 = $('<ul />');
                        var child1child1child = $('<li />', {text: "Address: " + data[i].Address});
                        child1child1.append(child1child1child);
                        var child1child1child2 = $('<li />', {text: "Cost: " + data[i].Cost});
                        child1child1.append(child1child1child2);
                        var child1child1child3 = $('<li />', {text: "Levels: " + data[i].NumberOfLevels});
                        child1child1.append(child1child1child3);
                        var child1child1child4 = $('<li />', {text: "Comments: " +data[i].Comments});
                        child1child1.append(child1child1child4);
                        child1.append(child1child1);
                        var p = $('<span />', {text: "Approve Request", id:"U" + data[i].RequestID, class: "submit" });
                        child1.append(p);
                        p = $('<span />', {text: "Deny Request", id:"D" + data[i].RequestID, class: "submit", style: "margin-left:10px;margin-top:10px;margin-bottom:10px;" });
                        child1.append(p);




                        parentList.append(child1);
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
