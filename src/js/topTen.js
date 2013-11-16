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
        var url = 'getTop10Users.php';
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {
                data = request.responseText;
                data = JSON.parse(data);
                data = data.TopTen;
                for(var i =0; i < data.length; i++){
                        var parentList = $('#topTenList');
                        var lastName = data[i].LastName;
                        lastName = lastName.charAt(0);
                        var child1 = $('<li />', {text: data[i].FirstName + " " + lastName + "." + " - " + data[i].Points + " Points"});
                        parentList.append(child1);
                }
            }
        }



    }

    
    loadList();
    userLogged();
}
window.onload = init;
