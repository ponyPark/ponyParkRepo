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
		<script type="text/javascript" src="js/includes.js"></script>
        <script type="text/javascript" src="js/verifySignup.js"></script>
		<script type="text/javascript" src="js/google.js"></script>
	</head>
	<body>
        <div id="logo"><!-- logo.html --></div>

		<div class="hpContent" id="signInHP">

    		<p class="details">Already registered? Log in, or sign in with Facebook or Google.</p>
    		<?
                if ($_GET["login"] === "false")
                    echo "<span id= 'badLogin' >Incorrect login information.<BR>Please try again.</span>";
            ?>
    		<form id="signInForm" method="POST" action="verify.php">
    		    <input type="email" name="email" title="Enter the email address associated with your account" placeholder="Email Address" required><input type="password" name="pw" placeholder = "Password" required>
    		    <input type="submit" value="Log In" class="submit">
    		</form>
            <br>
            <center>
                <span id="signinButton">
                    <span
                        class="g-signin"
                        data-callback="signinCallback"
                        data-clientid="273917884931.apps.googleusercontent.com"
                        data-cookiepolicy="single_host_origin"
                        data-requestvisibleactions="http://schemas.google.com/AddActivity"
                        data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
                    </span>
                </span>
                [FB sign in]
            </center>

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
			<span id="signupResult"></span>
			<form id="createAccount" name="createAcct" method="POST">
                <input type="text" name="fname" pattern="[a-zA-Z]+" title="Letters only" placeholder="First Name" required>
                <input type="text" name="lname" placeholder="Last Name" title="Letters only" pattern="[a-zA-Z]+"required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="pw" placeholder="Password" pattern=".{8,}" title="Need at least 8 characters"required>
                <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" title="10 Digit Phone Number, Numbers only">
                <!--
                     NOTE: The captcha will not work unless you are accessing PonyPark from localhost (can't be a custom virtual host name). It will work fine on the production server.
                -->
                <br><br>
                <script type="text/javascript">
                 var RecaptchaOptions = {
                    theme : 'white'
                 };
                 </script>
                <script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=6LfeVsgSAAAAACXVEPcFgYrFI3143x3cPT1ZKgxq'></script>
                <noscript>
                    <iframe src='http://www.google.com/recaptcha/api/noscript?k=6LfeVsgSAAAAACXVEPcFgYrFI3143x3cPT1ZKgxq' height='300' width='500' frameborder='0'></iframe><br>
                    <textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea>
                    <input type='hidden' name='recaptcha_response_field' value='manual_challenge'>
                </noscript>
                <br>
                <input class="submitButton" type="submit" value="Sign Up" class="submit" id="cSub">
            </form>
		</div>

	</body>

	<footer>
		PonyPark | Southern Methodist University | Dallas, Texas 2013
		<p id ="footerLinks"> <a href="http://nhl.com">Contact Us</a> <a href="http://nhl.com">Policies</a></p>
    </footer>

</html>

