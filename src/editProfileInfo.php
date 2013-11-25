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
        <script type="text/javascript" src="js/editUser.js"></script>
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

        <div class="hpContent" id="whatsPonyPark">
            <h2>Edit Your Profile Info</h2>
                <span id="signupResult"></span>
                <form id="submitEditUser" method="POST">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" pattern="[a-zA-Z]+" title="Letters only" placeholder="First Name" class="editProfileInput" required><br>
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" placeholder="Last Name" title="Letters only" pattern="[a-zA-Z]+" class="editProfileInput" required><br>
                <label for="phone">Phone Number:</label>
                <input type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10}" title="10 Digit Phone Number, Numbers only" class="editProfileInput"><br>
                <div id="changePassword">
                    <p>If you want to change your password, please enter your current password, then enter your new password.</p>
                    <label for="old_pw">Current password:</label>
                    <input type="password" name="old_pw" placeholder="Current Password" class="editProfileInput"><br>
                    <label for="new_pw">New password:</label>
                    <input type="password" name="new_pw" placeholder="New Password" pattern=".{8,}" title="Need at least 8 characters" class="editProfileInput"><br>
                </div>
                <br>
                <input type="submit" d="subRequestBut" class="submit" value="Submit Changes">
                <a href="viewProfileInfo.php" class="submit">View Profile</a>
                </form>
        </div>

    </body>

    <footer id="footer"><!-- footer.html --></footer>

</html>

