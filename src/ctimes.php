<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script type="text/javascript" src="js/Acommute.js"></script>
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
			<h2>Add Notification Times</h2>

			<p class = "details"><? 
			if($_GET['stat'] == 1) echo("**Your Notification Time Addition Was Successful**<BR>"); 
			if($_GET['stat'] == 2) echo("**Your Notification Time Addition Contained a Duplicate.  The duplicate was not added.**<BR>");
			?>
				Please select the time you'd like to be notified of parking availability:
				<p><input type="time" value="11:30" id="wTime"></p>
				<p> Please select what days of the week your commute begins at this time:</p>
						<input type="checkbox" value="Sunday" id="Sunday">Sunday</input>
 					 	<input type="checkbox" value="Monday" id="Monday">Monday</input>
				  	  	<input type="checkbox" value="Tuesday" id="Tuesday">Tuesday</input>
					  	<input type="checkbox" value="Wednesday" id="Wednesday">Wednesday</input>
					  	<input type="checkbox" value="Thursday" id="Thursday">Thursday</input>
					  	<input type="checkbox" value="Friday" id="Friday">Friday</input>
					  	<input type="checkbox" value="Saturday" id="Saturday">Saturday</input>
					  </p>
				<span class="submit" id="submitCommute">Submit</span>
				</p>




		</div>



	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a></p>
	</footer>

</html>

