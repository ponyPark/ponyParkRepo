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
                        var child1 = $('<li />', {text: "Name of Garage: " + data[i].Name});
                        var child1child1 = $('<ul />');
                        var child1child1child = $('<li />', {text: "Address: " + data[i].Address});
                        child1child1.append(child1child1child);
                        var child1child1child2 = $('<li />', {text: "Cost: " + cost});
                        child1child1.append(child1child1child2);
                        var child1child1child3 = $('<li />', {text: "Levels: " + data[i].NumberOfLevels});
                        child1child1.append(child1child1child3);
                        var child1child1child4 = $('<li />', {text: "Comments: " + comments});
                        child1child1.append(child1child1child4);
                        var child1child1child5 = $('<li />', {text: "Status: " + status});
                        child1child1.append(child1child1child5);
                        child1.append(child1child1);




                        parentList.append(child1);
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
