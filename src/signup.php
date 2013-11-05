<!-- PonyPark by BAM Software -->


<?
session_start();

// Redirect the user to the home page if they are logged in.
if($_SESSION['logged'] === true)
{
    header ('Location: index.php');
}
?>

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

		<div class="hpContent" id="signInHP">

    		<p class="details">Already Registered? Log In.</p>
    		<?
                if ($_GET["login"] === "false")
                    echo "<span id= 'badLogin' >Incorrect login information.<BR>Please try again.</span>";
            ?>
    		<form id="signInForm" method="POST" action="verify.php">
    		    <!--Also will need option for fbook/twitter login once we get that far -->
    		    <input type="email" name="email" title="Enter the email address associated with your account" placeholder="Email Address" required><input type="password" name="pw" placeholder = "Password" required>
    		    <input type="submit" value="Log In" class="submit">
    		</form>

		</div>

		<nav>

			<ol>
				<li><a href="signup.php">Join</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="request.php">Request</a></li>
			</ol>
		</nav>

		<div class="hpContent" id="singUpHP">
			<p class = "details">Use the signup form below to create a PonyPark Account.</p>
			<?php
				if ($_GET['error'] === "true")
					echo "<span id='badSignup'>That email address is already in use.</span>";
			?>
			<form id="createAccount" name="createAcct" method="POST" action="create.php">
                <input type="text" name="fname" pattern="[a-zA-Z]+" title="Letters only" placeholder="First Name" required>
                <input type="text" name="lname" placeholder="Last Name" title="Letters only" pattern="[a-zA-Z]+"required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="pw" placeholder="Password" pattern=".{8,}" title="Need at least 8 characters"required>
                <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" title="10 Digit Phone Number, Numbers only" required>
                <input class="submitButton" type="submit" value="Sign Up" class="submit" id="cSub">
            </form>
		</div>

	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a></p>
    </footer>

</html>

