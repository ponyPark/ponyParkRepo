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
	                document.getElementById('reportAva').innerHTML = data.ParkingInfo.Name;
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
                    
                    var average_rating = parseInt(data.LevelInfo[0].Average_Rating);
                    var latest_rating = parseInt(data.LevelInfo[0].Latest_Rating);
                    var rating = !average_rating ? latest_rating : average_rating;
                    var rating_message;
                    if (average_rating)
                        rating_message = "Average";
                    else
                        rating_message = "Most Recent (" + data.LevelInfo[0].Last_Rated + ")";
                    if(rating === 1) rating = "Full";
                    else if(rating === 2) rating = "Scarce";
                    else if(rating === 3) rating = "Some";
                    else if(rating === 4) rating = "Plenty";
                    else if(rating === 5) rating = "Empty";

                    var info = $("<li />", {
                                    text: "Level " + i + ": "});
                    if (!rating)
                    {
                        info = "<font class='levelNum'>Level " + i + 
                               "</font><br><font class='ratingValue'>Be the first to rate this level!</font><br><br>";
                    }
                    else
                    {
                        info = "<font class='levelNum'>Level " + i + 
                               "</font><br><font class='ratingMessage'>" + 
                               rating_message + ":</font> <font class='ratingValue'>" + 
                               rating + "</font><br><br>";
                    }

                    olElm.innerHTML += info;
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
                    ratings[index] = new Object();
                    ratings[index].y = parseInt(averages[i].Rating, 10);

                    var ratingDescription;
                    if (ratings[index].y === 1)
                        ratingDescription = "Full";
                    else if (ratings[index].y === 2)
                        ratingDescription = "Scarce";
                    else if (ratings[index].y === 3)
                        ratingDescription = "Some";
                    else if (ratings[index].y === 4)
                        ratingDescription = "Plenty";
                    else if (ratings[index].y === 5)
                        ratingDescription = "Empty";
                    else
                        ratingDescription = "No rating";

                    ratings[index].name = ratingDescription;
                }

                console.log(data);
                
                var chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'graph',
                        type: 'column',
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: ['12 AM', '1 AM', '2AM', '3AM', '4 AM', '5 AM',
                    '6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM', '8 PM', '9 PM', '10 PM', '11 PM']
                    },
                    yAxis: {
                        title: {
                            text: 'Rating (Full-Empty)'
                        },
                        max: 5,
                        categories: ['No rating', 'Full', 'Scarce', 'Some', 'Plenty', 'Empty'],
                        tickmarkPlacement: 'on'
                    },
                    legend: {
                        enabled: false
                    },  
                    series: [{
                    data: ratings,
                    name: "Rating"
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
                    addButton();

                }
                if (data === "false") {
                    $('#signIn').css('display', 'block');
                    $('#userOptions').css('display', 'none');
                    // Hide rating form if user is not logged in.
                    $("#ratingSection").html("<h2>PonyPark Needs You!</h2><font class='biggerFontForStory'>Please help contribute to PonyPark.  PonyPark relies on users just like you to report the current parking conditions on campus.  All you need to do is join or sign in to get started!</font>");
                }

            }
        }


    }


populateGarage();
userLogged();
}
window.onload = init;
