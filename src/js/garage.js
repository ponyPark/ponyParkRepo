/*BAM Software JS for PonyPark
October, 2013*/
function init() {
    function populateGarage(){


    		var parkID = document.getElementById('garageID').innerHTML;
	       var request = new XMLHttpRequest();
	       //change this URL depending on what Jessica sets it from PHPApi, just pass parkID as one of the git parameters.
	       var url = 'getParkingInfo.php';
	       var data;
           var gLevels;
       

	       request.open("GET", url+'?parkingID='+parkID, false);
	       request.send();
	      	if(request.readyState === 4){
	                //here you'll add the necessary form elements to the form ID reportAvaForm
	                //also change the innerHTML of elemnt with ID reportAva to "Rate the Availability of " + garage_name_from_xml_http_request
	                //Do radio buttons for the availability.
	                //You'll need to make sure you have a hidden field that passes the garageID to the DB side.
	                data = request.responseText;
	                data = JSON.parse(data);
                    var parkingCostVar = "The cost to visitors for this garage is $"+ data.ParkingInfo.Cost + ".";
	                document.getElementById('reportAva').innerHTML = "Rate the Availability of " + data.ParkingInfo.Name;
                    document.getElementById('address').innerHTML = data.ParkingInfo.Address + "<BR>Dallas, Texas 75205";
                    if(data.ParkingInfo.Cost === null){
                        parkingCostVar = "";
                    }
                    $('#moreGInfo').text(data.ParkingInfo.Name + " is located at " + data.ParkingInfo.Address + " in Dallas, Texas on the Southern Methodist University Campus. "+ parkingCostVar);
	                var text = "NONE";
                    var rating = data.ParkingInfo.Average_Rating ? data.ParkingInfo.Average_Rating : data.ParkingInfo.Latest_Rating;
                    if(rating === '1') text = "FULL";
                    if(rating === '2') text = "SCARCE";
                    if(rating === '3') text = "SOME";
                    if(rating === '4') text = "PLENTY";
                    if(rating === '5') text = "EMPTY";
                    if(text === "NONE") $('#ratingofG').text("There is no rating available for this garage. You can help by rating above.");
                    $('#ratingGinInfo').text(text);
                    var levels = document.getElementById('level');
	                var numLevels = parseInt(data.ParkingInfo.NumberOfLevels, 10);
                    gLevels = numLevels;
	                for (var i = 1; i <= numLevels; i++) {
	                	$('<option />', {
	                		value: i,
	                		text: i }).appendTo(levels);
	                }
	                
	      	}
            url = 'getLevelRating.php';
            var olElm = document.getElementById("levelRating");

              for (var i = 1 ; i <= gLevels; i++ ){
                
                    

                var child = $('<ul />');
                request = new XMLHttpRequest();
                url = url +'?parkingID='+parkID+'&level='+i;
                request.open("GET", url, false);
                request.send();
                if(request.readyState === 4){
                    data = request.responseText;
                    data = JSON.parse(data);
                    var text = "NONE";
                    var rating = data.LevelInfo[0].Average_Rating;
                    if(rating === '1') text = "FULL";
                    if(rating === '2') text = "SCARCE";
                    if(rating === '3') text = "SOME";
                    if(rating === '4') text = "PLENTY";
                    if(rating === '5') text = "EMPTY";
                    if(text === "NONE") text = "The average rating cannot be calculated due to lack of ratings. You can help by rating above.";
                    var latestRating = "NONE";
                    rating = data.LevelInfo[0].Latest_Rating;
                    if(rating === '1') latestRating = "FULL";
                    if(rating === '2') latestRating = "SCARCE";
                    if(rating === '3') latestRating = "SOME";
                    if(rating === '4') latestRating = "PLENTY";
                    if(rating === '5') latestRating = "EMPTY";
                    if(latestRating === "NONE") latestRating = "There is no rating available for this level. You can help by rating above.";

                    var time = "";
                    if ( data.LevelInfo[0].Last_Rated != null){
                        time = data.LevelInfo[0].Last_Rated;
                    }

                   var parent;
                    var child = $('<ul />');
                    var c1 = $('<li />', {
                        text: "The Average Rating: " + text}).appendTo(child);
                    var c2 = $('<li />', {
                        text: "The Most Recent Rating: " +latestRating + " "+ time}).appendTo(child);
                        parent = $('<li />', {
                            text: "Rating Data for Level " + i});

                    child.appendTo(parent);
                    parent.appendTo(olElm);


                }
            }

            url = 'getAverage.php';
            request = new XMLHttpRequest();
            url = url +'?parkingID='+parkID;
            request.open("GET", url, false);
            request.send();
            if(request.readyState === 4){
                data = request.responseText;
                data = JSON.parse(data);

                var averages = data.Ratings;
                var ratings = new Array();
                for (var u = 0; u < 24; u++){
                    ratings[u] = null;
                }

                for (var i = 0, len = averages.length; i < len; i++) {
                    var index = averages[i].Hour;
                    ratings[index] = parseInt(averages[i].Rating, 10);
                }

                console.log(data);
                
                var chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'graph',
                        type: 'column',
                    },
                    title: {
                        text: 'Average Rating'
                    },
                    xAxis: {
                        categories: ['12 AM', '1 AM', '2AM', '3AM', '4 AM', '5 AM',
                    '6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM', '8 PM', '9 PM', '10 PM', '11 PM']
                    },
                    yAxis: {
                        title: {
                            text: 'Rating (1-5)'
                        }
                    },
                    legend: {
                        enabled: false
                    },  
                    series: [{
                    data: ratings
                    }]
                });           

            }   


    }


    function addButton(){
        var request = new XMLHttpRequest();
        var parkID = document.getElementById('garageID').innerHTML;
        var url = 'hasFavorite.php?parkingID=' + parkID;
        var data;

        request.open("GET", url, true);
        request.send();
        request.onreadystatechange = function (e) {

            if (request.readyState === 4) {
                data = request.responseText;
                if(data === "False"){
                    //not a favorite
                    var child = $('#insertButtonHere');
                    var link = $('<a />',{
                        href: "addFavorites.php?parkingID=" + parkID + "&redirect=false",
                        text: "Add To Favorites",
                        class: "submit"}).appendTo(child);
                }
                else{
                    //favorite
                    var child = $('#insertButtonHere');
                    var link = $('<a />',{
                        href: "deleteFavorites.php?favoriteID=" + data + "&redirect=false&parkingID=" + parkID,
                        text: "Delete from Favorites",
                        class: "submit"}).appendTo(child);
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
                    // Show rating form if user is logged in.
                    $('#notloggedGarageReport').css('display', 'none');
                    $('#reportAvaDivElem').css('display', 'block');
                    addButton();

                }
                if (data === "false") {
                    $('#signIn').css('display', 'block');
                    $('#userOptions').css('display', 'none');
                    // Hide rating form if user is not logged in.
                    $('#notloggedGarageReport').css('display', 'block');
                    $('#reportAvaDivElem').css('display', 'none');
                }

            }
        }


    }


populateGarage();
userLogged();
}
window.onload = init;
