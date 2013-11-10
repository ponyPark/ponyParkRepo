<!-- PonyPark by BAM Software 

	The data presented on this site can be modified.  This is just for a design feel.

September 14, 2013

-->

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
		<script type="text/javascript" src="js/google.js"></script>
	</head>
	<body>
		<h1><a href="index.php"><img src="PonyPark.png" alt="PonyPark Logo"></a></h1>
		<p id = "slogan">Trot Right In</p>

		<section id="signIn">
		Welcome to PonyPark, Guest! Please <a href="signup.php">Join</a> or <a href="signup.php">Sign in</a>
		
		<span id="signinButton">
		    <span
			    class="g-signin"
			    data-callback="signinCallback"
			    data-clientid="273917884931.apps.googleusercontent.com"
			    data-cookiepolicy="single_host_origin"
			    data-requestvisibleactions="http://schemas.google.com/AddActivity"
			    data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
		    </span>
		</span>
		
		</section>
		<div id="userOptions">
			Welcome back to PonyPark, <? session_start(); echo($_SESSION['userName']);?>!
			<ol id="userList">
				<li><a href="maccount.php">Manage Account</a></li>
				<li><a href="favlist.php">Favorite List</a></li>
				<li><a href="#" id="signOut">Sign Out</a></li>
			</ol>
		</div>

		<nav>

			<ol>
				<li><a href="signup.php">Join</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="request.php">Request</a></li>
			</ol>
		</nav>

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

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a>

</html>

