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
	</head>
	<body>
		<h1><a href="index.php"><img src="PonyPark.png" alt="PonyPark Logo"></a></h1>
		<p id = "slogan">Trot Right In</p>

		<section id="signIn">
		Already Registered? Log In.
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

		<div class="hpContent" id = "whatsPonyPark">
			<h2> What's PonyPark?</h2>
			<p class = "details">We've all been in that dreadful position; you need to be somewhere, but you can't find a parking spot.  We hate it when that happens.  Fortunately for you, we chose to do something to help solve this problem....PonyPark. </p>
			<p class = "details">
				PonyPark is a parking finder service.  The service is based on realtime data submitted by real people.  The very people who use the service are the ones who post the information.  Our service was built specifically for the students, faculty, and community members of Southern Methodist University.  Our service allows users to see the availability as reported by other users.  When you are on your way to campus, simply check PonyPark for the availability, and trot right in to your parking spot.  Remember to report the availability once parked so that other users may benefit.
			</p>




		</div>

		<div class="hpContent" id = "whenExpanding">
			<h2> Can I use PonyPark anywhere?</h2>
			<p class = "details">As of right now, PonyPark is being made to the specifics of Southern Methodist University.  However, we shoot for the stars.  Future releases will support more places.
			</div>



	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a>

</html>

