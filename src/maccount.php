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
        <div id="logo"><!-- logo.html --></div>

        <section id="signIn"><!-- dashboardSignIn.html --></section>
        <div id="userOptions"><!-- dashboardUserOptions.php --></div>
        <nav id="navigation"><!-- navigation.html --></nav>

        <div class="hpContent">
            <h2>Manage Account</h2>
            <p class = "details">Please choose an action you would like to perform:
                <ul>
                    <? session_start();
            if($_SESSION['userType'] == 1 && $_SESSION['logged'] == true) echo("<li><a href='admin.php'>Admin Dashboard</a></li>"); ?>
                    <li>Profile
                        <ul>
                            <li><a href="viewProfileInfo.php">View Your Profile Info</a></li>
                            <li><a href="editProfileInfo.php">Edit Your Profile Info</a></li>
                        </ul>
                    </li>
                    <li>Favorite Garages
                        <ul>
                            <li><a href="favlist.php">View Favorites</a></li>
                            <li><a href="editfav.php">Add/Remove Favorites</a></li>
                        </ul>
                    </li>
                    <li>Notification Settings
                        <ul>
                            <li><a href="Ectimes.php">View/Edit/Delete Notification Times</a></li>
                            <li><a href="ctimes.php">Add Notification Time</a></li>
                        </ul>
                    </li>
                    <li>Request Garage Additions
                        <ul>
                            <li><a href="requestStat.php">Status of Garage Addition Requests</a></li>
                            <li><a href="request.php">Request Addition of a Garage to PonyPark</a></li>
                        </ul>
                    </li>
                </ul></p>
        </div>
    </body>

    <footer id="footer"><!-- footer.html --></footer>

</html>

