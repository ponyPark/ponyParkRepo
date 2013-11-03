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
			Already Registered? Log In.
			<form id="signInForm" method="POST" action="verify.php">
				<!--Also will need option for fbook/twitter login once we get that far -->
			Email:<input type="email" name="email" title="Enter the email address associated with your account" required> Password:<input type="password" name="pw" required>
				<input type="submit" value="Log In"></form>
		</section>
		<div id="userOptions">
			<h2>Welcome back to PonyPark, <? echo($_SESSION['userName']);?>!</h2>
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

		<div class="hpContent" id="reportAvaDivElem">
			<h2 id="reportAva">Rate the Availability of a Garage</h2>
			<p class = "details">Use the form below to rate the availbility of this garage.</p>
			<form id="reportAvaForm" name="reportAvailbilityForm" method="POST" action="addRating.php?parkingID=<?php echo $_GET['garageID']; ?>">
				<div>
					<label>Level</label>
					<select id="level" name="level">
					</select>
				</div>

				<div>
					<!--1 is worst 5 is best -->
					<label for="full">Full</label>
					<input type="radio" name="availability" id="full" value="1">
					<label for="scarce">Scarce</label>
					<input type="radio" name="availability" id="scarce" value="2">
					<label for="some">Some</label>
					<input type="radio" name="availability" id="some" value="3">
					<label for="plenty">Plenty</label>
					<input type="radio" name="availability" id="plenty" value="4">
					<label for="empty">Empty</label>
					<input type="radio" name="availability" id="empty" value="5">
				</div>

				<input type="submit" value="Rate" />

            </form>




		</div>



	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a>

</html>

