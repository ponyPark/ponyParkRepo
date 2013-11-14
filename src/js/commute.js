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

    function whatIsChecked(){
        var DOW = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var days = new Array();
        for(var i = 0; i<4; i++){
            
            if(document.getElementById(DOW[i]).checked === true){
                days.push(i + 1);
            }
            

    }
    if(days.length === 0 ){
        alert("You Must Select a Day of the Week.");
        return;
    }
    var time = document.getElementById('cTime').value;
    var warningTime = document.getElementById('wTime').value;

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
    window.location.href = 'ctimes.php?stat=1';
}


    userLogged();

    var subButton = document.getElementById("submitCommute");
    subButton.addEventListener("click", whatIsChecked, true);


}
window.onload = init;
