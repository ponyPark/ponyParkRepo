<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script type="text/javascript" src="js/requestStat.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/includes.js"></script>
		<script type="text/javascript" src="js/google.js"></script>
		<script type="text/javascript" src="js/facebook.js"></script>
	</head>
	<body>


		<?

				session_start();
				if($_SESSION['logged'] != true){
				    header ('Location: index.php');
				}

						?>




        <div id="logo"><!-- logo.html --></div>

		<section id="signIn"><!-- dashboardSignIn.html --></section>
		<div id="userOptions"><!-- dashboardUserOptions.php --></div>
		<nav id="navigation"><!-- navigation.html --></nav>

		<div class="hpContent" id = "whatsPonyPark">
			<h2>Request Status</h2>
			<p class = "details">
				The status of your garage addition(s) are below:</p>
			<UL id="requestStatus"></UL>
		</div>

	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

