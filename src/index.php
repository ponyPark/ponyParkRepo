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
		<script type="text/javascript" src="js/script.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBpuJSTZynN7SwEyy1dYFYR9ysrWJ3Aq0I&sensor=false"></script>	
		
	</head>
	<body>
		<h1><a href="index.php"><img src="PonyPark.png" alt="PonyPark Logo"></a></h1>
		<p id = "slogan">Trot Right In</p>

		<section id="signIn">
		Already Registered? Log In.
		<?
                if ($_GET["login"] === "false")
                    echo "<span id= 'badLogin' >Incorrect login information.  Please try again.</span>";
            ?>
			<form id="signInForm" method="POST" action="verify.php">
				<!--Also will need option for fbook/twitter login once we get that far -->
			Email:<input type="email" name="email" title="Enter the email address associated with your account" required> Password:<input type="password" name="pw" required>
				<input type="submit" value="Log In">
			</form>

		</section>
		<div id="userOptions">
			<h2>Welcome back to PonyPark, <? session_start(); echo($_SESSION['userName']);?>!</h2>
			<ol id="userList">
				<li><a href="http://nhl.com">Manage Account</a></li>
				<li><a href="http://nhl.com">Favorite List</a></li>
				<li><a href="signOut.php">Sign Out</a></li>
			</ol>
		</div>

		<nav>
			<ol>
				<li><a href="signup.php">Signup</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="http://nhl.com">Contact</a></li>
				<li><a href="http://nhl.com">Request</a></li>
			</ol>
		</nav>

		<div class="hpContent">
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

