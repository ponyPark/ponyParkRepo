<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBpuJSTZynN7SwEyy1dYFYR9ysrWJ3Aq0I&sensor=false"></script>
		<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/includes.js"></script>
		<script type="text/javascript" src="js/google.js"></script>
		<script type="text/javascript" src="js/facebook.js"></script>
	</head>
	<body>
        <div id="logo"><!-- logo.html --></div>

		<section id="signIn"><!-- dashboardSignIn.html --></section>
		<div id="userOptions"><!-- dashboardUserOptions.php --></div>
		<nav id="navigation"><!-- navigation.html --></nav>

		<div class="hpContent" id="frontpg">
			<h2>Parking Availability</h2>
			<p class = "details">Use the map below or the list view to the right to see the parking availability.</p>
			<div id="map-canvas"></div>
			<div id="listView">
				<!-- This list will be dynamically implemented from DB using javascript 
					If a garage's rating is scarce/full make red
					some - yellow
					plenty/empty - green
					This will likely be implement in iteration 2 because it requires calculation algorithim.  Hard code to examine aesthetic effects for now

					All garages will point to the same link: garage.php, but will have its garage ID (received from DB) appended to the link.  
					For example, Garage with ID 7 will have the link garage.php?garageID=7
					This will be very easy to have garage.php populate the request page because we just get all the info from the DB using the ID.

				!-->
				<ol id="garageList">
					
				</ol>
			</div>
	</div>



	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

