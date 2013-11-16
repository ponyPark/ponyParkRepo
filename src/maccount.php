<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
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

		<div class="hpContent">
			<h2>Manage Account</h2>
			<p class = "details">Please choose an action you would like to perform:
				<ul>
					<? session_start();
			if($_SESSION['userType'] == 1 && $_SESSION['logged'] == true) echo("<li><a href='admin.php'>Admin Dashboard</a></li>"); ?>
					<li><a href="editfav.php">Add/Remove Favorites</a></li>
					<li>Notification Settings
						<UL>
							<LI><a href="ctimes.php">Add Notification Time</a></LI>
							<LI><a href="Ectimes.php">Edit/Delete Notification Times</a></LI>

						</UL></li>
					<li><a href="request.php">Request Addition of a Garage to PonyPark</a></li>
					
				</ul></p>
		</div>
	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

