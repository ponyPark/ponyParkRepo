<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script type="text/javascript" src="js/Ecommute.js"></script>
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

<section id="cTimeContainer">
		<div class="hpContent">
			<h2>Modify/Delete Notification Times</h2>

			<p class = "details" id="mainPar"><? 
			if($_GET['stat'] == 1) echo("<b>**Your Notification Time Modification Was Successful**</b><BR>"); 
			if($_GET['stat'] == 2) echo("<b>**Your Notification Time Modification Contained a Duplicate.  The duplicate was not added.**</b><BR>");?>
				Please use the form below to modify your notification times.
				</p>




		</div>
</section>


	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

