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


		<?

				session_start();
				if($_SESSION['logged'] != true){
				    header ('Location: error.html');
				}

						?>

        <div id="logo"><!-- logo.html --></div>

		<section id="signIn"><!-- dashboardSignIn.html --></section>
		<div id="userOptions"><!-- dashboardUserOptions.php --></div>
		<nav id="navigation"><!-- navigation.html --></nav>

		<div class="hpContent" id = "whatsPonyPark">
			<h2>Add a Garage to the PonyPark System</h2>
			<p class = "details">
				<? 
			if($_GET['stat'] == 1) echo("**Your request was sent and is being reviewed**<BR>"); ?>
				Please fill out all required fields.  </p>
				<p>Please ensure your garage is located on the SMU Campus or your request will be denied.  Please do not include any zip codes, states, or cities when submitting the address. All fields are required except Comments and Cost.
				<form id="submitRequest" method="POST" action="addRequest.php">
				<input type="text" name="name" id="garageName" pattern="[a-z A-Z]+" title="Letters only" placeholder="Name of Garage" required>
                <input type="text" name="address" id="streetAddress" placeholder="Street Address" title="House Number and Street, no city/state/zip" pattern="[a-z A-Z0-9]+" required>
                <input type="text" name="cost" id="costOfG" placeholder="Cost" pattern="[0-9.]+" title="Please enter in cost format D.CC">
                <input type="tel" name="numLevels" id="levelsOfG" placeholder="Number of levels" pattern="[0-9]+" title="Please enter numbers only" required>
                <input type="text" name="comments" id="comments" placeholder="Comments" title="Enter any comments, 250 characters or less" maxlength="250" style="width: 300px;">
                <input type="submit" d="subRequestBut" class="submit" value="Submit Request">
                </form>


			</p>
			
		</div>

	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

