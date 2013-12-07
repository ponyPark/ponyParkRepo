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
        <script type="text/javascript" src="js/facebook.js"></script>
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
                <fb:login-button perms="email" width="200" max-rows="1" size="large"></fb:login-button>
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
                <br>
                <input class="submitButton" type="submit" value="Sign Up" class="submit" id="cSub">
            </form>
		</div>

	</body>

	<footer id="footer"><!-- footer.html --></footer>

</html>

