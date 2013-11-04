/*BAM Software JS for PonyPark
October, 2013*/
function init() {
    function populateGarage(){


    		var parkID = document.getElementById('garageID').innerHTML;
	       var request = new XMLHttpRequest();
	       //change this URL depending on what Jessica sets it from PHPApi, just pass parkID as one of the git parameters.
	       var url = 'getParkingInfo.php';
	       var data;
       

	       request.open("GET", url+'?parkingID='+parkID, true);
	       request.send();
	       request.onreadystatechange = function(e) {

	      	if(request.readyState === 4){
	                //here you'll add the necessary form elements to the form ID reportAvaForm
	                //also change the innerHTML of elemnt with ID reportAva to "Rate the Availability of " + garage_name_from_xml_http_request
	                //Do radio buttons for the availability.
	                //You'll need to make sure you have a hidden field that passes the garageID to the DB side.
	                data = request.responseText;
	                data = JSON.parse(data);
	                //data = ({"ParkingInfo":{"ParkingID":"1","Name":"Moody Garage","Address":"6004 Bishop Blvd","Cost":"2","NumberOfLevels":"4"}});
	                console.log(data);
	                document.getElementById('reportAva').innerHTML = "Rate the Availability of " + data.ParkingInfo.Name;
                    document.getElementById('address').innerHTML = data.ParkingInfo.Address + "<BR>Dallas, Texas 75205";
                    $('#moreGInfo').text(data.ParkingInfo.Name + " is located at " + data.ParkingInfo.Address + " in Dallas, Texas on the Southern Methodist University Campus");
	                var text = "NONE";
                    if(data.ParkingInfo.Rating === '1') text = "FULL";
                    if(data.ParkingInfo.Rating === '2') text = "SCARCE";
                    if(data.ParkingInfo.Rating === '3') text = "SOME";
                    if(data.ParkingInfo.Rating === '4') text = "PLENTY";
                    if(data.ParkingInfo.Rating === '5') text = "EMPTY";
                    if(text === "NONE") $('#ratingofG').text("There is no rating available for this garage. You can help by loggin in and rating above.");
                    console.log(text);
                    $('#ratingGinInfo').text(text);
                    var levels = document.getElementById('level');
	                var numLevels = parseInt(data.ParkingInfo.NumberOfLevels, 10);
	                for (var i = 1; i <= numLevels; i++) {
	                	$('<option />', {
	                		value: i,
	                		text: i }).appendTo(levels);
	                }
	                
	      	}
	   	}
	        

    }

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
                    $('#reportAvaDivElem').css('display','none');
                    $('#userOptions').css('display', 'none');
                    $('#notloggedGarageReport').css('display', 'block');
                }

            }
        }


    }


populateGarage();
userLogged();
}
window.onload = init;
