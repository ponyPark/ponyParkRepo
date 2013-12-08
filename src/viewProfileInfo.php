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
        <script type="text/javascript" src="js/viewUser.js"></script>
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
            <h2>View Your Profile Info</h2>
                <div class="biggerFontForStory">
                    <b>First Name:</b> <span id="firstName"></span><br><br>
                    <b>Last Name:</b> <span id="lastName"></span><br><br>
                    <b>Email:</b> <span id="email"></span><br><br>
                    <b>Phone Number:</b> <span id="phoneNumber"></span><br><br>
                    <b>User Type:</b> <span id="userType"></span><br><br>
                    <b>Account Type:</b> <span id="accountType"></span><br><br>
                </div>
            <a href="editProfileInfo.php" class="submit">Edit Profile Info</a>
        </div>

    </body>

    <footer id="footer"><!-- footer.html --></footer>

</html>

