<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script type="text/javascript" src="js/garage.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/includes.js"></script>
		<script type="text/javascript" src="js/google.js"></script>
		<script type="text/javascript" src="js/facebook.js"></script>
		<script src="http://code.highcharts.com/2.2.4/highcharts.js"></script>
	</head>
	<body>
		<div>
			<?

				session_start();
				//if($_SESSION['logged'] != true){
				    //we'll have to disable submit button if the user is not logged in.
				//}


                echo "<span id= 'garageID' style='display:none;'>" . $_GET["garageID"] . "</span>";


						?>
		</div>
        
        <div id="logo"><!-- logo.html --></div>
		
		<section id="signIn"><!-- dashboardSignIn.html --></section>
		<div id="userOptions"><!-- dashboardUserOptions.php --></div>
		<nav id="navigation"><!-- navigation.html --></nav>
        
        <div class="hpContent" id="notloggedGarageReport" style="display:none;">
            <h2>PonyPark Needs You!</h2>
            Please help contribute to PonyPark.  PonyPark relies on users just like you to report the current parking conditions on campus.  All you need to do is join or sign in to get started!
        </div>

		<div class="hpContent" id="reportAvaDivElem">
			<h2 id="reportAva">Rate the Availability of a Garage</h2>
            <div id="addressAndImage">
                <div id="garageImgAndLevelInfo">
                    <img height="200" width="400" id="garageImg" src="garage_images/<?php if (file_exists("garage_images/" . $_GET["garageID"] . ".jpg")) 
                                                                                            echo $_GET['garageID'] . ".jpg";
                                                                                          else
                                                                                            echo "noimage.jpg"; 
                                                                                        ?>">
                
                    <div id="levelRating"><h2>Level Information</h2></div>
                </div>
                <h3 id="address"></h3>
                <p id="ratingofG">The current rating of the garage is <span id="ratingGinInfo"></span>.</p>
            </div>
            <div id="ratingSection">
                <h2>Rate the Availability</h2>
                <form id="reportAvaForm" name="reportAvailbilityForm" method="POST" action="addRating.php?parkingID=<?php echo $_GET['garageID']; ?>">
    				<div>
    					<label class="biggerFontForStory">Which level did you park your car?</label>
    					<select id="level" name="level">
    					</select>
    				</div>
    				<p class = "details biggerFontForStory">How would you rate the availability of parking?</p>
    				<div>
    					<!--1 is worst 5 is best -->
                        <input type="radio" id="rateRadioButton" name="availability" id="full" value="1" title="There was no place to park." checked>
    					<label for="full" title="There was no place to park." class="biggerFontForStory">Full garage</label>
                        <br>
                        <input type="radio" id="rateRadioButton" name="availability" id="scarce" value="2" title="Found a spot, but others may not be so lucky.">
    					<label for="scarce" title="Found a spot, but others may not be so lucky." class="biggerFontForStory">Scarce parking spots</label>
                        <br>
                        <input type="radio" id="rateRadioButton" name="availability" id="some" value="3" title="There were a lot of cars, but I found a spot.">
    					<label for="some" title="There were a lot of cars, but I found a spot." class="biggerFontForStory">Some parking spots</label>
                        <br>
                        <input type="radio" id="rateRadioButton" name="availability" id="plenty" value="4" title="I had no trouble finding a spot.">
    					<label for="plenty" title="I had no trouble finding a spot." class="biggerFontForStory">Plenty parking spots</label>
                        <br>
                        <input type="radio" id="rateRadioButton" name="availability" id="empty" value="5" title="The garage is basically empty, plenty of spots.">
    					<label for="empty" title="The garage is basically empty, plenty of spots." class="biggerFontForStory">Empty garage</label>
    				</div>
                    <br>
    				<input type="submit" value="Rate" class="submit"/>
                </form>
                <br><br>
                <h2>Favorite This Garage</h2>
                <font class="biggerFontForStory">If you like this garage, add it to your favorites!</font>
                <p id="insertButtonHere"></p>
            </div>
            <br><br>
            <h2>Average Rating For Each Hour of the Day</h2>
            <div id="graph" />
            </form>


		</div>
	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

