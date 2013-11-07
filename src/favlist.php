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
		<script type="text/javascript" src="js/favlist.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<h1><a href="index.php"><img src="PonyPark.png" alt="PonyPark Logo"></a></h1>
		<p id = "slogan">Trot Right In</p>

		<section id="signIn">
		Welcome to PonyPark, Guest! Please <a href="signup.php">Join</a> or <a href="signup.php">Sign in</a>
		</section>
		<div id="userOptions">
			Welcome back to PonyPark, <? session_start(); echo($_SESSION['userName']);?>!
			<ol id="userList">
				<li><a href="maccount.php">Manage Account</a></li>
				<li><a href="favlist.php">Favorite List</a></li>
				<li><a href="signOut.php">Sign Out</a></li>
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

		<div class="hpContent" >
			<h2>Favorite List</h2>
			<p class = "details">
				Below are your favorite garages.  Click on a garage to get more detailed real time info.  Click <a href="editfav.php">Here</a> to edit your favorites.

				<ul id="favListDisp">
				</ul>
			</p>




		</div>




	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a></p>
	</footer>

</html>

