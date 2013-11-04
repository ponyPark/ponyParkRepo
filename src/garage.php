<!-- PonyPark by BAM Software 

	

-->




<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script type="text/javascript" src="js/garage.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
			<h1><a href="index.php"><img src="PonyPark.png" alt="PonyPark Logo"></a></h1>
		<p id = "slogan">Trot Right In</p>
		<section id="signIn">
		Welcome to PonyPark, Guest! Please <a href="signup.php">Join</a> or <a href="signup.php">Sign in</a>
		</section>
		<div id="userOptions">
			Welcome back to PonyPark, <? session_start(); echo($_SESSION['userName']);?>!
			<ol id="userList">
				<li><a href="http://nhl.com">Manage Account</a></li>
				<li><a href="http://nhl.com">Favorite List</a></li>
				<li><a href="signOut.php">Sign Out</a></li>
			</ol>
		</div>

		<nav>
			<ol>
				<li><a href="signup.php">Join</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="http://nhl.com">Contact</a></li>
				<li><a href="http://nhl.com">Request</a></li>
			</ol>
		</nav>

		<div class="hpContent" id="reportAvaDivElem">
			<h2 id="reportAva">Rate the Availability of a Garage</h2>
			<h3 id="address"></h3>
			<p class = "details">Please answer the two simple questions below to report the availability of this garage.</p>
			<form id="reportAvaForm" name="reportAvailbilityForm" method="POST" action="addRating.php?parkingID=<?php echo $_GET['garageID']; ?>">
				<div>
					<label>Which level did you park your car?</label>
					<select id="level" name="level">
					</select>
				</div>
				<p class = "details">How would you rate the availability of parking?</p>
				<div>
					<!--1 is worst 5 is best -->
					<label for="full" title="There was no place to park.">Full</label>
					<input type="radio" name="availability" id="full" value="1" title="There was no place to park." checked>
					<label for="scarce" title="Found a spot, but others may not be so lucky.">Scarce</label>
					<input type="radio" name="availability" id="scarce" value="2" title="Found a spot, but others may not be so lucky.">
					<label for="some" title="There were a lot of cars, but I found a spot.">Some</label>
					<input type="radio" name="availability" id="some" value="3" title="There were a lot of cars, but I found a spot.">
					<label for="plenty" title="I had no trouble finding a spot.">Plenty</label>
					<input type="radio" name="availability" id="plenty" value="4" title="I had no trouble finding a spot.">
					<label for="empty" title="The garage is basically empty, plenty of spots.">Empty</label>
					<input type="radio" name="availability" id="empty" value="5" title="The garage is basically empty, plenty of spots.">
				</div>

				<input type="submit" value="Rate" class="submit"/>

            </form>




		</div>

		<div class="hpContent" id="notloggedGarageReport" style="display:none;">
			<h2>PonyPark Needs You!</h2>
			Please help contribute to PonyPark.  PonyPark relies on users just like you to report the current parking conditions on campus.  All you need to do is join or sign in to get started!
		</div>

		<div class="hpContent">
			<p id="moreGInfo"></p>
			<p id="ratingofG">The current rating of the garage is <span id="ratingGinInfo"></span></p>

		</div>



	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a>

</html>

