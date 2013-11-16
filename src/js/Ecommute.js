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

    function populateCommuteTimes(){
        var request = new XMLHttpRequest();
        var url = 'getCommuteTimes.php';
        var data;

        request.open("GET", url, false);
        request.send();
            if (request.readyState === 4) {
                var data = request.responseText;
                data = JSON.parse(data);
                data = data.CommuteTimes;
                

            }

            if(data.length === 0 ){
                $('#mainPar').text("Whoops, you don't have any commute times.  ");
                $('#mainPar').append($('<a />', {text: "Click here to add some.", href: "ctimes.php"}));
                return;
            }

        for(var i = 0; i < data.length; i++){
            var dayOfWeek = data[i].Day;
            var parent = $('<div />', { class: "hpContent"}).append($("<h2 />", {text: "Commute Time " + (i+1) }));
            var p1 = $('<p />', { text: "Modify your current times and hit update to save the changes.  To delete a specific commute time, hit delete", class: "details"});
            var p1p = $('<p />', {text: "Your commute time is set to:"});
            p1p.append($('<input />', {type: "time", value: data[i].Time, id: "cTime" + data[i].CommuteID}));
            var p2p = $('<p />', {text: "Your current notification time is set to:"});
            p2p.append($('<input />', {type: "time", value: data[i].WarningTime, id: "wTime" + data[i].CommuteID}));
            var p3p = $('<p />', {text: "Your current commute days are:"});
            var divElm = $('<p />');
            var input = $('<input />', { type: 'checkbox', id: '1'+data[i].CommuteID, value: "Sunday" })
            var label = $('<label />', { 'for': '1'+data[i].CommuteID, text: "Sunday" });
            divElm.append(input);
            divElm.append(label);
            input = $('<input />', { type: 'checkbox', id: '2'+data[i].CommuteID, value: "Monday" })
            label = $('<label />', { 'for': '2'+data[i].CommuteID, text: "Monday" });
            divElm.append(input);
            divElm.append(label);
            input = $('<input />', { type: 'checkbox', id: '3'+data[i].CommuteID, value: "Tuesday" })
            label = $('<label />', { 'for': '3'+data[i].CommuteID, text: "Tuesday" });
            divElm.append(input);
            divElm.append(label);
            input = $('<input />', { type: 'checkbox', id: '4'+data[i].CommuteID, value: "Wednesday" })
            label = $('<label />', { 'for': '4'+data[i].CommuteID, text: "Wednesday" });
            divElm.append(input);
            divElm.append(label);
            input = $('<input />', { type: 'checkbox', id: '5'+data[i].CommuteID, value: "Thursday" })
            label = $('<label />', { 'for': '5'+data[i].CommuteID, text: "Thursday" });
            divElm.append(input);
            divElm.append(label);
            input = $('<input />', { type: 'checkbox', id: '6'+data[i].CommuteID, value: "Friday" })
            label = $('<label />', { 'for': '6'+data[i].CommuteID, text: "Friday" });
            divElm.append(input);
            divElm.append(label);
            input = $('<input />', { type: 'checkbox', id: '7'+data[i].CommuteID, value: "Saturday" })
            label = $('<label />', { 'for': '7'+data[i].CommuteID, text: "Saturday" });
            divElm.append(input);
            divElm.append(label);
            var p = $('<p />').append($('<span />', {text: "Update", id:"U" + data[i].CommuteID, class: "submit" }));
            divElm.append(p);
            p = $('<p />').append($('<span />', {text: "Delete This Time", id:"D" + data[i].CommuteID, class: "submit" }));
            divElm.append(p);

            p3p.append(divElm);

            p1.append(p1p);
            p1.append(p2p);
            p1.append(p3p);
            parent.append(p1);

            $( "#cTimeContainer" ).append(parent);

            $("#" + dayOfWeek + data[i].CommuteID).prop('checked', true);

            var editButton = document.getElementById("U" + data[i].CommuteID);
            editButton.addEventListener("click", editCommute, true);

            var delButton = document.getElementById("D" + data[i].CommuteID);
            delButton.addEventListener("click", deleteCommutePressed, true);
        }


}
    function editCommute(e){
        var id = e.target.id;
        var idSub = id.substr(1);
        deleteCommute(idSub);
        var DOW = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var days = new Array();
        for(var i = 0; i<7; i++){
            
            if(document.getElementById((i+1) + "" + idSub).checked === true){
                days.push(i + 1);
            }
            

    }
    if(days.length === 0 ){
        alert("You Must Select a Day of the Week.");
        return;
    }
    var time = document.getElementById('cTime' + idSub).value;
    var warningTime = document.getElementById('wTime' + idSub).value;

    var passArray;
    passArray = {
        "days": days,
        "time": time,
        "warningTime": warningTime

    };
    var request = new XMLHttpRequest();
    var passing = 'commutes=';
    var ArrayToPass = JSON.stringify(passArray)
    passing += ArrayToPass;
        //console.log(passing);
    request.open("POST", 'addCommuteTimes.php', false);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(passing);
    //console.log(JSON);
    if (request.readyState === 4) {
                var data = request.responseText;
                if(data === ""){
                    window.location.href = 'Ectimes.php?stat=1';
                }
                else(window.location.href = 'Ectimes.php?stat=2');
                

            }
    }

    function deleteCommute(idPassed){

        var request = new XMLHttpRequest();
        var url = "deleteCommuteTimes.php?commuteID=" + idPassed;
        request.open("GET", url, false);
        request.send();


    }

    function deleteCommutePressed(e){

        var id = e.target.id;
        var idSub = id.substr(1);
        deleteCommute(idSub);
        window.location.href = 'Ectimes.php?stat=1';


    }



    userLogged();
    populateCommuteTimes();


}
window.onload = init;
