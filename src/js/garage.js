/*BAM Software JS for PonyPark
October, 2013*/
function init() {
    function populateGarage(){


    		var parkID = document.getElementById('garageID').innerHTML;
    		console.log(parkID);
	       var request = new XMLHttpRequest();
	       //change this URL depending on what Jessica sets it from PHPApi, just pass parkID as one of the git parameters.
	       var url = '';
	       var data;

	       request.open("GET", url, true);
	       request.send();
	       request.onreadystatechange = function(e) {

	      	if(request.readyState === 4){
	                //here you'll add the necessary form elements to the form ID reportAvaForm
	                //also change the innerHTML of elemnt with ID reportAva to "Rate the Availability of " + garage_name_from_xml_http_request
	                //Do radio buttons for the availability.
	                //You'll need to make sure you have a hidden field that passes the garageID to the DB side.

	      	}
	   	}
	        

    }


populateGarage();
}
window.onload = init;
