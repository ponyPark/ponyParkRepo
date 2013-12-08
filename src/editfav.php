<!-- PonyPark by BAM Software -->

<!DOCTYPE html>

<html lang="en">
	<head>
		<title>PonyPark | Trot Right In</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script type="text/javascript" src="js/favorite.js"></script>
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
			<h2>Edit Favorite Garages</h2>
            <a class="submit" href="favlist.php">View Favorites</a>

			<p class = "details"><? 
			if($_GET['stat'] == 1) echo("<b>**Your Favorite Modification Was Successful**</b><BR>"); ?>
				Please select the garages you wish to keep in your favorites.
				
				<ul id="favList">
				</ul>
				</p>




		</div>



	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

