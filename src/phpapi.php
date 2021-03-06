<?
/** 
 * Designed by BAM Software.
 * This file contains the api for all our php functions.
 * There are other php files that access these functions separately.
 */
class phpapi
{
    private $con;

    /**
     * The constructor for our php api. Opens a connection to the mySQL
     * database. 
     */
    function phpapi()
    {
        ob_start();
        session_start();

        $this->con = mysqli_connect("localhost", "root", "root", "PonyPark")
                    or die ("Error: " . mysqli_error($con));
    }

    /**
     * Verify the response to the captcha.
     * @return boolean True if the answer was correct, false otherwise.
     */
    public function checkCaptcha()
    {
        require_once("library/recaptchalib.php");
        $captcha=recaptcha_check_answer("6LfeVsgSAAAAAM0146TD70YoisPsPDkRmYGg_zg6", 
            $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], 
            $_POST["recaptcha_response_field"]);
        if ($captcha->is_valid)
            return true;
        else
            return false;
    }

    /**
     * The function to add a user into the database using the information
     * sent through the POST. Using queries to insert into the table.
     */
    public function addUser()
    {   
        // Obtain user info
        $fname = mysqli_real_escape_string($this->con, $_POST['fname']);
        $lname = mysqli_real_escape_string($this->con, $_POST['lname']);
        $email = mysqli_real_escape_string($this->con, $_POST['email']);
        $phone = mysqli_real_escape_string($this->con, $_POST['phone']);
        $raw_pw = $_POST['pw'];

        // Check to make sure all required values have been inputted.
        if (empty($fname) || empty($lname) || empty($email) || strlen($raw_pw)<8)
        {
            return false;
        }

        // Salt and hash the password.
        $salt = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB), MCRYPT_DEV_RANDOM);
        $pwps = $raw_pw . $salt;
        $pw = hash(md5, $pwps);
        
        // Insert the user into the database.
        $auth = 0;
        $externalType = "Native";
        $query = "INSERT INTO Users(FirstName, LastName, Email,
            Password,PasswordSalt,PhoneNumber,UserType,ExternalType) VALUES 
            ('$fname','$lname','$email','$pw','$salt','$phone','$auth','$externalType')";
        if(!mysqli_query($this->con, $query))
        {
            return false;
        }
        else
        {
            return true; 
        }
    }

    /**
     * Get the first name, last name, email, phone number, and external type of 
     * the logged in user.
     * @return JSON The profile of the logged in user.
     */
    public function getUser()
    {
        //Get userID from SESSION
        $userID = $_SESSION['userID'];

        //Query to get user's information
        $query = "SELECT FirstName, LastName, Email, PhoneNumber, UserType, ExternalType 
            FROM Users WHERE Users.UserID = '$userID'";
        $result = mysqli_query($this->con, $query);

        //Change mysql result to array so that it can be exported in JSON.
        $rows = mysqli_fetch_assoc($result);
        return json_encode(array('UserInfo' => $rows));
    }

    /**
     * Edit the user's profile info. Any user can edit their first name, last name,
     * and phone number. The first name and last name can't be blank. Only 
     * non-Google and non-Facebook users can change their password.
     * @return string Empty string: Editing the user was successful.
     *                missing_info: First name or last name was missing.
     *                wrong_password: User wanted to change their password and 
     *                  the password confirmation failed.
     */
    public function editUser()
    {
        //Get userID from SESSION
        $userID = $_SESSION['userID'];

        //Get information from POST
        $firstName = mysqli_real_escape_string($this->con, $_POST['fname']);
        $lastName = mysqli_real_escape_string($this->con, $_POST['lname']);
        $phoneNumber = $_POST['phone'];
        $oldPassword = $_POST['old_pw'];
        $newPassword = $_POST['new_pw'];

        if(empty($firstName) || empty($lastName))
            return "missing_info";

        //Check if phoneNumber is null
        $phoneNumber = empty($phoneNumber) ? "NULL" : "'" . $phoneNumber . "'";

        if(empty($newPassword))
        {
            $query = "UPDATE Users SET FirstName = '$firstName', LastName = 
                '$lastName', PhoneNumber = $phoneNumber WHERE UserID = '$userID'";
            mysqli_query($this->con, $query);
        }
        else
        {
            // Obtain the salt for the user.
            $query = "SELECT * FROM Users WHERE UserId = '$userID'";
            $info = mysqli_fetch_array(mysqli_query($this->con, $query));
            $salt = $info['PasswordSalt'];

            // Add the salt to the password, and hash the whole thing.
            $pwps = $oldPassword . $salt;
            $pw = hash(md5, $pwps);

            // Validate the user. Using the native system.
            $query = "SELECT * FROM Users WHERE UserID = '$userID' AND 
                Password = '$pw'";
            $result = mysqli_query($this->con, $query);

            if(mysqli_num_rows($result) != 0)
            {
                // Create the salt. Then salt and hash the password.
                $salt = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB), MCRYPT_DEV_RANDOM);
                $pwps = $newPassword . $salt;
                $pw = hash(md5, $pwps);

                $query = "UPDATE Users SET FirstName = '$firstName', LastName = 
                    '$lastName', PhoneNumber = $phoneNumber, Password = '$pw', 
                    PasswordSalt = '$salt' WHERE UserID = '$userID'";
                mysqli_query($this->con, $query);
            }
            else
                return "wrong_password";
        }

        // Update the session variable with the new name.
        $_SESSION['userName'] = $_POST['fname'];
        
        return "";
    }

    /**
     * This function processes a Google+ sign in. If an entry doesn't already
     * exist in the Users table for the user, then an entry is created.
     * It will set session variables to indicate a logged in user.
     * @return boolean True if user was successfully logged in through Gogogle.
     * False if there was an error or if session variables were already set.
     */
    public function checkGoogleUser()
    {
        
        if(!isset($_SESSION['logged']) || !isset($_SESSION['userEmail']) || 
            !isset($_SESSION['userID']) || !isset($_SESSION['userName']))
        {
            // Obtain user info
            $fname = mysqli_real_escape_string($this->con, $_POST['fname']);
            $lname = mysqli_real_escape_string($this->con, $_POST['lname']);
            $email = mysqli_real_escape_string($this->con, $_POST['email']);
            $externalID = mysqli_real_escape_string($this->con, $_POST['externalID']);
            $auth = 0;
            $externalType = "Google";

            if(empty($email) || empty($externalID))
                return false;

            //Query to see if the user already exists
            $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
                ExternalType = '$externalType'";
            $result = mysqli_query($this->con, $query);

            //If the user doesn't exist.
            if(mysqli_num_rows($result)==0)
            {
                //Add the info into the users table.
                $query = "INSERT INTO Users(FirstName, LastName, Email, UserType,
                    ExternalType, ExternalID) VALUES ('$fname','$lname','$email','$auth',
                    '$externalType', '$externalID')";
                mysqli_query($this->con, $query);

                //Get everything from the row just inserted.
                $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
                    ExternalType = '$externalType'";
                $result = mysqli_query($this->con, $query);
            }

            $info = mysqli_fetch_array($result);
            $_SESSION['logged'] = true;
            $_SESSION['userEmail'] = $info['Email'];
            
            //Added the user to the session since we use
            //that for adding favorites, etc.
            $_SESSION['userType'] = $info['UserType'];
            $_SESSION['userID'] = $info['UserID'];
            $_SESSION['userName'] = $info['FirstName'];

            if ($info['UserType'] == "1")
                return "admin";
            else
                return "user";
        }
        return false;
    }

    /**
     * This function processes a Facebook sign in. If an entry doesn't already
     * exist in the Users table for the user, then an entry is created.
     * It will set session variables to indicate a logged in user.
     * @return boolean True if user was successfully logged in through Facebook.
     * False if there was an error or if session variables were already set.
     */
    public function checkFacebookUser()
    {
        
        if(!isset($_SESSION['logged']) || !isset($_SESSION['userEmail']) || 
            !isset($_SESSION['userID']) || !isset($_SESSION['userName']))
        {
            // Obtain user info
            $fname = mysqli_real_escape_string($this->con, $_POST['fname']);
            $lname = mysqli_real_escape_string($this->con, $_POST['lname']);
            $email = mysqli_real_escape_string($this->con, $_POST['email']);
            $externalID = mysqli_real_escape_string($this->con, $_POST['externalID']);
            $auth = 0;
            $externalType = "Facebook";

            if(empty($email) || empty($externalID))
                return false;

            //Query to see if the user already exists
            $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
                ExternalType = '$externalType'";
            $result = mysqli_query($this->con, $query);

            //If the user doesn't exist.
            if(mysqli_num_rows($result)==0)
            {
                //Add the info into the users table.
                $query = "INSERT INTO Users(FirstName, LastName, Email, UserType,
                    ExternalType, ExternalID) VALUES ('$fname','$lname','$email','$auth',
                    '$externalType', '$externalID')";
                mysqli_query($this->con, $query);
                
                //Get everything from the row just inserted.
                $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
                    ExternalType = '$externalType'";
                $result = mysqli_query($this->con, $query);
            }

            $info = mysqli_fetch_array($result);
            $_SESSION['logged'] = true;
            $_SESSION['userEmail'] = $info['Email'];
            
            //Added the user to the session since we use
            //that for adding favorites, etc.
            $_SESSION['userType'] = $info['UserType'];
            $_SESSION['userID'] = $info['UserID'];
            $_SESSION['userName'] = $info['FirstName'];

            if ($info['UserType'] == "1")
                return "admin";
            else
                return "user";
        }
        return false;
    }

    /**
     * This function processes a Google+ sign in from Android. If an entry 
     * doesn't already exist in the Users table for the user, then an entry is 
     * created. It will set session variables to indicate a logged in user.
     * @return JSON All the user data so the android app can use it
     */
    public function verifyGoogleUserAndroid()
    {
        // Obtain user info
        $fname = mysqli_real_escape_string($this->con, $_POST['fname']);
        $lname = mysqli_real_escape_string($this->con, $_POST['lname']);
        $email = mysqli_real_escape_string($this->con, $_POST['email']);
        $externalID = mysqli_real_escape_string($this->con, $_POST['externalID']);
        $auth = 0;
        $externalType = "Google";

        //Query to see if the user already exists
        $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
            ExternalType = '$externalType'";
        $result = mysqli_query($this->con, $query);

        //If the user doesn't exist.
        if(mysqli_num_rows($result)==0)
        {
            //Add the info into the users table.
            $query = "INSERT INTO Users(FirstName, LastName, Email, UserType,
                ExternalType, ExternalID) VALUES ('$fname','$lname','$email','$auth',
                '$externalType', '$externalID')";
            mysqli_query($this->con, $query);

            //Get everything from the row just inserted.
            $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
                ExternalType = '$externalType'";
            $result = mysqli_query($this->con, $query);
        }

        $info = mysqli_fetch_array($result);

        // Change mysql result to array so that it can be exported in JSON.
        // Returns an empty array if no info is inside.
        return json_encode(array('UserInfo' => $info));
    }

    /**
     * This function processes a Facebook sign in from Android. If an entry 
     * doesn't already exist in the Users table for the user, then an entry is 
     * created.  It will set session variables to indicate a logged in user.
     * @return JSON All the user data so the android app can use it
     */
    public function verifyFacebookUserAndroid()
    {
        // Obtain user info
        $fname = mysqli_real_escape_string($this->con, $_POST['fname']);
        $lname = mysqli_real_escape_string($this->con, $_POST['lname']);
        $email = mysqli_real_escape_string($this->con, $_POST['email']);
        $externalID = mysqli_real_escape_string($this->con, $_POST['externalID']);
        $auth = 0;
        $externalType = "Facebook";

        if(empty($email) || empty($externalID))
            return false;

        //Query to see if the user already exists
        $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
            ExternalType = '$externalType'";
        $result = mysqli_query($this->con, $query);

        //If the user doesn't exist.
        if(mysqli_num_rows($result)==0)
        {
            //Add the info into the users table.
            $query = "INSERT INTO Users(FirstName, LastName, Email, UserType,
                ExternalType, ExternalID) VALUES ('$fname','$lname','$email','$auth',
                '$externalType', '$externalID')";
            mysqli_query($this->con, $query);
            
            //Get everything from the row just inserted.
            $query = "SELECT * FROM Users WHERE ExternalID = '$externalID' AND 
                ExternalType = '$externalType'";
            $result = mysqli_query($this->con, $query);
        }

        $info = mysqli_fetch_array($result);

        // Change mysql result to array so that it can be exported in JSON.
        // Returns an empty array if no info is inside.
        return json_encode(array('UserInfo' => $info));
    }

    /**
     * A function to verify that a user is entering the right information when
     * logging in. By retrieving information from a query to the database. It 
     * also saves the information into a session. 
     * @return boolean True on successful login, false otherwise.
     */
    public function verifyUser()
    {
        // Check that email and password are not empty.
        $email = mysqli_real_escape_string($this->con, $_POST['email']);
        $raw_password = $_POST['pw'];
        if (empty($email) || empty($raw_password))
        {
            header ('Location: signup.php?login=false');
            return false;
        }

        // Obtain the salt for the user.
        $query = "SELECT * FROM Users WHERE Email = '$email' AND 
            ExternalType = 'Native'";
        $info = mysqli_fetch_array(mysqli_query($this->con, $query));
        $salt = $info['PasswordSalt'];
        $info = NULL;

        // Add the salt to the password, and hash the whole thing.
        $pwps = $raw_password . $salt;
        $pw = hash(md5, $pwps);

        // Validate the user. Using the native system.
        $query = "SELECT * FROM Users WHERE Email = '$email' AND    
            ExternalType = 'Native' AND Password = '$pw'";
        $result = mysqli_query($this->con, $query);

        // Redirect the user to an error if validation was unsucessful.
        // Otherwise, set the logged in state by setting session variables,
        // then redirect the user to the home page.
        if(mysqli_num_rows($result) == 0)
        {
            header ('Location: signup.php?login=false');
            return false;
        }
        else
        {
            $info = mysqli_fetch_array($result);
            $_SESSION['logged'] = true;
            $_SESSION['userEmail'] = $info['Email'];
            //Added the user to the session since we use
            //that for adding favorites, etc.
            $_SESSION['userType'] = $info['UserType'];
            $_SESSION['userID'] = $info['UserID'];
            $_SESSION['userName'] = $info['FirstName'];
            if ($info['UserType'] == "1")
                header ('Location: admin.php');
            else
                header ('Location: index.php');
            return true;
        }
    }

    /**
     * verify a user and start a new session for Android
     * @return JSON All the user data so the android app can use it
     */
    public function verifyUserAndroid()
    {
        $email = mysqli_real_escape_string($this->con, $_POST['email']);
        $raw_password = $_POST['pw'];

        // Obtain the salt for the user.
        $query = "SELECT * FROM Users WHERE Email = '$email' AND 
            ExternalType = 'Native'";
        $info = mysqli_fetch_array(mysqli_query($this->con, $query));
        $salt = $info['PasswordSalt'];
        $info = NULL;
        
        // Add the salt to the password, and hash the whole thing.
        $pwps = $raw_password . $salt;
        $pw = hash(md5, $pwps);

        // Validate the user. Using the native system.
        $query = "SELECT * FROM Users WHERE Email = '$email' AND    
            ExternalType = 'Native' AND Password = '$pw'"; 
        $result = mysqli_query($this->con, $query);
        
        if(mysqli_num_rows($result) > 0)
        {
            //Get the user info.
            $info = mysqli_fetch_assoc($result);

            // We need to keep all the session varaibles despite outputting the 
            // JSON because the server will need to be "involved" as well.
            $_SESSION['logged'] = true;
            $_SESSION['userEmail'] = $info['Email'];
            
            // Added the user to the session since we use
            // that for adding favorites, etc.
            $_SESSION['userID'] = $info['UserID'];
            $_SESSION['userName'] = $info['FirstName'];
            
        }

        // Change mysql result to array so that it can be exported in JSON.
        // Returns an empty array if no info is inside.
        return json_encode(array('UserInfo' => $info));
    }

    /**
     * A function to see if the user is logged in or not.  
     * @return boolean If false, the user is not logged in.
     */
    public function userStatus()
    {
        if($_SESSION['logged'] == true ) return "true";
        else return "false";
    }

    /**
     * A function to sign out by deleting the session.
     */
    public function signOut()
    {
        if(isset($_SESSION['logged']) || isset($_SESSION['userEmail']) || 
            isset($_SESSION['userID']) || isset($_SESSION['userName']))
        {
            $_SESSION = array();
            session_destroy();
            return true;
        }
        else return false;
    }

     public function signOutAndroid()
    {
        $_SESSION = array();
        session_destroy();
    }

    /**
     * A function to get information for a specific parking location given a
     * ParkingID.
     * @param INT $parkingID The ID of the parking location.
     * @return JSON The information for the requested parking location.
     */
    public function getParkingInfo($parkingID)
    {
        // Get the parking information for the requested garage.
        $query = "SELECT *, (SELECT floor(avg(Rating)) AS Rating FROM
            Ratings WHERE Timestamp>DATE_SUB(NOW(), INTERVAL 2 HOUR) AND
            Ratings.ParkingID = ParkingLocations.ParkingID) AS Average_Rating,
            IFNULL((SELECT Rating FROM Ratings WHERE ParkingLocations.ParkingID
            = Ratings.ParkingID ORDER BY Timestamp DESC LIMIT 1), 5) AS 
            Latest_Rating, IFNULL((SELECT IF(HOUR(TIMEDIFF(NOW(), timestamp))<24, 
            CONCAT(HOUR(TIMEDIFF(NOW(), timestamp)), ' hours ago'), 
            '>24 hours ago') FROM Ratings WHERE Ratings.ParkingID = 
            ParkingLocations.ParkingID ORDER BY timestamp DESC LIMIT 1), '>24 hours ago') AS 
            Last_Rated FROM ParkingLocations WHERE ParkingLocations.parkingID
            = '$parkingID'";
        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = mysqli_fetch_assoc($result);
        return json_encode(array('ParkingInfo' => $rows));
    }

    /**
     * A function to get the ratings for a specific parking location's levels 
     * given a ParkingID.
     * @param INT $parkingID The ID of the parking location.
     * @return JSON The information for the requested parking location's levels.
     */
    public function getLevelRating($parkingID, $level)
    {
        // Get the parking information for the requested garage.
        $query = "SELECT (SELECT floor(avg(Rating)) AS Rating FROM
            Ratings WHERE Timestamp>DATE_SUB(NOW(), INTERVAL 2 HOUR) AND
            Ratings.ParkingID = ParkingLocations.ParkingID AND Ratings.Level = 
            '$level') AS Average_Rating, (SELECT Rating FROM Ratings WHERE 
            ParkingLocations.ParkingID = Ratings.ParkingID AND Ratings.Level = 
            '$level' ORDER BY Timestamp DESC LIMIT 1) AS Latest_Rating,
            (SELECT IF(HOUR(TIMEDIFF(NOW(), timestamp))<24, 
            CONCAT(HOUR(TIMEDIFF(NOW(), timestamp)), ' hour(s) ago'), 
            '>24 hours ago') FROM Ratings WHERE Ratings.ParkingID = 
            ParkingLocations.ParkingID AND Ratings.Level = '$level' 
            ORDER BY timestamp DESC LIMIT 1) AS Last_Rated FROM 
            ParkingLocations WHERE ParkingLocations.parkingID = '$parkingID'";
        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('LevelInfo' => $rows));
    }

    /**
     * A function to get the list of parking locations for map and list view. 
     * @return JSON A list of the different locations and their ratings.
     */
    public function getParkingLocations()
    {
        
        //Get the average ratings from the past 2 hours or the most recent
        //Rating if the previous is NULL. Also get the ID, Name, and Address for
        //every garage.
        $query = "SELECT ParkingLocations.ParkingID, ParkingLocations.Name, 
            ParkingLocations.Address, (SELECT floor(avg(Rating)) FROM
            Ratings WHERE Timestamp>DATE_SUB(NOW(), INTERVAL 2 HOUR) AND
            Ratings.ParkingID = ParkingLocations.ParkingID) AS Average_Rating,
            IFNULL((SELECT Rating FROM Ratings WHERE ParkingLocations.ParkingID
            = Ratings.ParkingID ORDER BY Timestamp DESC LIMIT 1), 5) AS Latest_Rating, 
            IFNULL((SELECT IF(HOUR(TIMEDIFF(NOW(), timestamp))<24, 
            CONCAT(HOUR(TIMEDIFF(NOW(), timestamp)), ' hours ago'), 
            '>24 hours ago') FROM Ratings WHERE Ratings.ParkingID = 
            ParkingLocations.ParkingID ORDER BY timestamp DESC LIMIT 1), 
            '>24 hours ago') AS Last_Rated FROM ParkingLocations ORDER BY ParkingLocations.Name";

        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('ParkingLocations' => $rows));
    }

    /**
     * Edit a parking location.
     * @param INT $parkingID The ID of a parking location.
     * @return boolean True on success, false on error.
     */
    public function editParkingLocation($parkingID)
    {
        // Get the JSON object for the edited parking location info.
        $parkingInfoJSON = $_POST['parkingInfo'];
        if (empty($parkingInfoJSON)) return false;

        // Read the JSON.
        $parkingInfo = (array) json_decode($parkingInfoJSON);
        $name = mysqli_real_escape_string($this->con, $parkingInfo['name']);
        $address = mysqli_real_escape_string($this->con, $parkingInfo['address']);
        $cost = $parkingInfo['cost'];
        $numberOfLevels = $parkingInfo['numberOfLevels'];

        //Change cost if NULL
        $cost = empty($cost) ? "NULL" : "'" . $cost . "'";

        // Edit the parking location info.
        $query = "UPDATE ParkingLocations SET Name = '$name', 
            Address = '$address', Cost = '$cost', NumberOfLevels = 
            '$numberOfLevels' WHERE ParkingID = '$parkingID'";
        if (mysqli_query($this->con, $query))
            return true;
        else
            return false;
    }

    /**
     * A function to add a rating to the database.
     * @param INT $parkingID The ID of the parking location.
     */
    public function addRating($parkingID)
    {
        $ratingInfoJSON = $_POST;

        // Retrieve the values from the session and the post.
        $userID = $_SESSION['userID'];


        $query = "INSERT INTO Ratings (ParkingID, Level, Timestamp, UserID, 
            Rating) VALUES ('$parkingID', '" . $_POST['level'] . 
            "', NOW(), '$userID', '" . $_POST['availability'] . "')";
        mysqli_query($this->con, $query);
        header ('Location: ratingsub.html'); //this will need to change 
    }

    /**
     * A function to add a rating to the database for Android.
     * @param INT $userID The ID of the user.
     * @param INT $parkingID The ID of the parking location.
     */
    public function addRatingAndroid($userID, $parkingID)
    {
        $ratingInfoJSON = $_POST;

        $query = "INSERT INTO Ratings (ParkingID, Level, Timestamp, UserID, 
            Rating) VALUES ('$parkingID', '" . $_POST['level'] . 
            "', NOW(), '$userID', '" . $_POST['availability'] . "')";
        mysqli_query($this->con, $query); 
    }

     /**
     * A function to add a request for a parking garage.
     */
    public function addRequest()
    {


        // Retrieve the UserID.
        $userID = $_SESSION['userID'];



        //Change comments and cost if NULL
        $cost = empty($_POST['cost']) ? "NULL" : "'" . $_POST['cost'] . "'";
        $comments = empty($_POST['comments']) ? "NULL" : "'" .
            mysqli_real_escape_string($this->con, $_POST['comments']) . "'";
        
        //Query to insert request into the table
        $query = "INSERT INTO Requests (UserID, Name, Address, Cost, 
            NumberOfLevels, Comments, Status) VALUES ('$userID', '" . 
            mysqli_real_escape_string($this->con, $_POST['name']) . "','" .
            mysqli_real_escape_string($this->con, $_POST['address']) . "', $cost,'" . 
            $_POST['numLevels'] . "', $comments, 0)"; 
        mysqli_query($this->con, $query);
    }

    /**
     * Gets all the info on requests for parking garages that a user has made.
     * @return JSON A list of requested garages by a user.
     */
    public function getRequests()
    {
        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        // Get the list of requests that a user has made.
        $query = "SELECT Name, Address, Cost, NumberOfLevels, Comments, Status 
            FROM Requests WHERE UserID = '$userID' ORDER BY RequestID DESC";
        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('RequestedGarages' => $rows));
    }

    /**
     * Gets all requests with status of 0 for Admins to view.
     * @return JSON A list of all the requests with status 0.
     */
    public function getAllRequests()
    {
        // Get the list of requests.
        $query = "SELECT RequestID, Name, Address, Cost, NumberOfLevels, Comments, Status 
            FROM Requests WHERE Status = 0 ORDER BY RequestID ASC";
        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('Requested' => $rows));        
    }

    /**
     * Allows Admin to change status for parking location requests.
     * @param INT $requestID The requestID for a request to be reviewed.
     * @param INT $status The status of the request to be reviewed.
     */
    public function editRequests($requestID, $status)
    {
        $query = "UPDATE Requests SET Status = $status WHERE RequestID = $requestID";
        mysqli_query($this->con, $query);

        if($status == 2)
        {
            $query = "INSERT INTO ParkingLocations (Name, Address, Cost, 
                NumberOfLevels) SELECT Name, Address, Cost, NumberOfLevels FROM 
                Requests WHERE RequestID = $requestID";
            mysqli_query($this->con, $query);
        }
    }

    /**
     * Add a favorite garage for a user.
     * @param INT $parkingID The ID of the parking location.
     */
    public function addFavorites($parkingID)
    {
        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        //Get the highest priority plus 1
        $query = "SELECT IFNULL(max(Priority)+1,1) priority FROM FavoriteGarages
            WHERE UserID = '$userID'";
        $priority = mysqli_fetch_assoc(mysqli_query($this->con, $query));

        //Add a favorite garage.
        $query = "INSERT INTO FavoriteGarages(UserID, ParkingID, Priority)
            VALUES('$userID', '$parkingID', '" . $priority["priority"] . "')";
        mysqli_query($this->con, $query);    
    }

    /**
     * Get the favorite garages for a user.
     * @return JSON The list of favorite garages.
     */
    public function getFavorites()
    {
        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        //Get the list of favorite garages.
        $query = "SELECT * FROM FavoriteGarages WHERE UserID = '$userID' ORDER BY Priority";          
        $result = mysqli_query($this->con, $query);    
    
        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('Favorites' => $rows));
    }

    /**
     * Delete a favorite garage for a user.
     * @param INT $favoriteID The ID of the favorite parking location.
     */
    public function deleteFavorites($favoriteID)
    {
        //Delete a favorite garage.
        $query = "DELETE FROM FavoriteGarages WHERE FavoriteID = '$favoriteID'";
        mysqli_query($this->con, $query);    
    }

    /**
     * Add a favorite garage for a user.
     * @param INT $userID The ID for a user.
     * @param INT $parkingID The ID of the parking location.
     */
    public function addFavoritesAndroid($userID, $parkingID)
    {
        //Get the highest priority plus 1
        $query = "SELECT IFNULL(max(Priority)+1,1) priority FROM FavoriteGarages
            WHERE UserID = '$userID'";
        $priority = mysqli_fetch_assoc(mysqli_query($this->con, $query));

        //Add a favorite garage.
        $query = "INSERT INTO FavoriteGarages(UserID, ParkingID, Priority)
            VALUES('$userID', '$parkingID', '" . $priority["priority"] . "')";
        mysqli_query($this->con, $query);    
    }

    /**
     * Get the favorite garages for a user.
     * @param INT $userID The ID for a user.
     * @return JSON The list of favorite garages.
     */
    public function getFavoritesAndroid($userID)
    {
        //Get the list of favorite garages.
        $query = "SELECT * FROM FavoriteGarages WHERE UserID = '$userID' ORDER BY Priority";          
        $result = mysqli_query($this->con, $query);    
    
        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('Favorites' => $rows));
    }

    /**
     * Returns whether or not a user has a garage in their favorites.
     * @param INT $userID The ID for a user.
     * @param INT $parkingID The ID of the parking location.
     * @return BOOL Returns true if the user has that garage in their favorites,
     * false if the user does not.
     */
    public function hasFavoriteAndroid($userID, $parkingID)
    {
        //Get the user's favorite, if it exsists, that corresponds with the parkingID
        $query = "SELECT * FROM FavoriteGarages WHERE parkingID = '$parkingID' 
            AND UserID = '$userID'";
        $result = mysqli_query($this->con, $query);
        $info = mysqli_fetch_assoc($result);

        //If the garage is not one of the user's favorites, return false
        if(mysqli_num_rows($result) == 0)
        {
            echo("False");
            return false;
        }
        
        //Return true if the garage is one of the user's favorites
        else
        {
            echo($info['FavoriteID']);
            return true;
        }
    }

    /**
     * Returns whether or not a user has a garage in their favorites.
     * @param INT $parkingID The ID of the parking location.
     * @return BOOL Returns true if the user has that garage in their favorites,
     * false if the user does not.
     */
    public function hasFavorite($parkingID)
    {
        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        //Get the user's favorite, if it exsists, that corresponds with the parkingID
        $query = "SELECT * FROM FavoriteGarages WHERE parkingID = '$parkingID' 
            AND UserID = '$userID'";
        $result = mysqli_query($this->con, $query);
        $info = mysqli_fetch_assoc($result);

        //If the garage is not one of the user's favorites, return false
        if(mysqli_num_rows($result) == 0)
            echo("False");
        
        //Return true if the garage is one of the user's favorites
        else echo($info['FavoriteID']);
    }

    /**
     * Add a commute time for a user. The user will be able to select multiple
     * days at a time for a single commute time. This function will handle that
     * as an array. Assumes that 1 is Sunday, 7 is Saturday.
     * @return String of any duplicates.
     */
    public function addCommuteTimes()
    {
        // Get the JSON object for the commute time.
        $commuteTimesJSON = $_POST['commutes'];
        if (empty($commuteTimesJSON)) return false;

        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        // Read the JSON.
        $commutes = (array) json_decode($commuteTimesJSON);

        // Get the list of days that the commute time applies to.
        $days = (array) $commutes['days'];

        // Get the warning time.
        $warningTime = $commutes['warningTime'];

        //Make an array for any duplicates.
        $existing = array();

        // Changes all values in the days array to (userID warningTime,
        // value) where value is a specific day
        foreach ($days as $value)
        {
            // Add the commute time.
            $query = "INSERT INTO CommuteTimes (UserID, WarningTime, Day)
            VALUES ('$userID', '$warningTime', '$value')";
            if (!mysqli_query($this->con, $query))
            {
                array_push($existing , $value);
            }
        }

        //Return a string of duplicate values.
        return implode(',', $existing);
    }

    /**
     * Gets a list of the commute times for the current user.
     * @return JSON The list of commute times for that user.
     */
    public function getCommuteTimes()
    {
        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        //Get the list of commute times.
        $query = "SELECT CommuteID, Day, WarningTime FROM CommuteTimes 
        WHERE UserID = '$userID'";          
        $result = mysqli_query($this->con, $query);
    
        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('CommuteTimes' => $rows));
    }

    /**
     * Delete a commute for a user.
     * @param INT $commuteID The ID of the commute for a user.
     */
    public function deleteCommuteTimes($commuteID)
    {
        //Delete a commute time.
        $query = "DELETE FROM CommuteTimes WHERE CommuteID = '$commuteID'";
        mysqli_query($this->con, $query);    
    }

    /**
     * Email all users the availability of their favorite garages before their
     * commute times, if they have commute time(s).
     */
    public function notifyUsers()
    {
        $query = "SELECT DISTINCT UserID, (SELECT Email FROM Users WHERE 
            Users.UserID=CommuteTimes.UserID) AS Email, (SELECT FirstName FROM 
            Users WHERE Users.UserID=CommuteTimes.UserID) AS FirstName, 
            (SELECT LastName FROM Users WHERE Users.UserID=CommuteTimes.UserID) 
            AS LastName, CommuteTimes.CommuteID FROM CommuteTimes WHERE 
            Day=DAYOFWEEK(NOW()) AND ABS(TIME_TO_SEC(TIMEDIFF(TIME(NOW()), 
            WarningTime)))<3*60 AND ABS(TIME_TO_SEC(TIMEDIFF(NOW(), 
            TimeOfNotification)))>5*60";
        $result = mysqli_query($this->con, $query);

        while ($row = mysqli_fetch_assoc($result))
        {
            //Query to get the ratings of a users favorite garage.
            $query = "SELECT ParkingLocations.Name, ParkingLocations.Address,
                FavoriteGarages.Priority, (SELECT floor(avg(Rating)) AS Rating FROM
                Ratings WHERE Timestamp>DATE_SUB(NOW(), INTERVAL 2 HOUR) AND
                Ratings.ParkingID = ParkingLocations.ParkingID) AS Average_Rating,
                IFNULL((SELECT Rating FROM Ratings WHERE ParkingLocations.ParkingID
                = Ratings.ParkingID ORDER BY Timestamp DESC LIMIT 1), 5) AS 
                Latest_Rating, IFNULL((SELECT IF(HOUR(TIMEDIFF(NOW(), timestamp))<24, 
                CONCAT(HOUR(TIMEDIFF(NOW(), timestamp)), ' hours ago'), 
                '>24 hours ago') FROM Ratings WHERE Ratings.ParkingID = 
                ParkingLocations.ParkingID ORDER BY timestamp DESC LIMIT 1), 
                '>24 hours ago') AS Last_Rated FROM ParkingLocations JOIN 
                FavoriteGarages WHERE ParkingLocations.parkingID = 
                FavoriteGarages.parkingID AND FavoriteGarages.UserID = '". 
                $row['UserID'] . "' ORDER BY FavoriteGarages.Priority";
            $result2 = mysqli_query($this->con, $query);

            $query = "UPDATE CommuteTimes SET TimeOfNotification = NOW() WHERE 
                CommuteID = '" . $row['CommuteID'] . "'";
            mysqli_query($this->con, $query);

            $message = "Hello " . $row['FirstName'] . " " . $row['LastName'] . 
                    "!\n\n";
            if(mysqli_num_rows($result2) == 0)
            {
                $message .= "You currently do not have any favorite garages. " .
                    "Therefore, we cannot send you any garage ratings at this time. " .
                    "If you would like to receive a rating for any garages in the " .
                    "future, please add the garages to your favorite garages.";
            }
            else
            {
                $message .= "Here's a list of the capacity of your favorite " . 
                    "garages!\n\n";
                while ($row2 = mysqli_fetch_assoc($result2))
                {
                    $message .= $row2['Name'] . " (" . $row2['Address'] . "):\n";

                    if(!empty($row2['Average_Rating']))
                    {
                        if($row2['Average_Rating'] == 1)
                            $rating = "Full Garage";
                        else if($row2['Average_Rating'] == 2)
                            $rating = "Scarce Parking Spots";
                        else if($row2['Average_Rating'] == 3)
                            $rating = "Some Parking Spots";
                        else if($row2['Average_Rating'] == 4)
                            $rating = "Plenty of Parking Spots";
                        else if($row2['Average_Rating'] == 5)
                            $rating = "Empty Garage";

                        $message .= "Average Rating for the past 2 hours: " . 
                            $rating . "\n\n";
                    }
                    else
                    {
                        if($row2['Latest_Rating'] == 1)
                            $rating = "Full Garage";
                        else if($row2['Latest_Rating'] == 2)
                            $rating = "Scarce Parking Spots";
                        else if($row2['Latest_Rating'] == 3)
                            $rating = "Some Parking Spots";
                        else if($row2['Latest_Rating'] == 4)
                            $rating = "Plenty of Parking Spots";
                        else if($row2['Latest_Rating'] == 5)
                            $rating = "Empty Garage";

                        $message .= "Latest Rating (" . $row2['Last_Rated'] . "): " . 
                            $rating . "\n\n";
                    }
                }
            }
            $message .= "If you no longer wish to receive these emails, delete ". 
                "your commute times at http://ponypark.floccul.us\n\nYours truly," .
                "\nPonyPark from BAM! Software";

            mail($row['Email'], "Notification from PonyPark", $message, 
                "From: ponypark@floccul.us");
            sleep(.5);
        }
    }

    /**
     * Get the Top Ten Users ranked by the number of contributions.
     * @return JSON The list top ten users.
     */
    public function getTop10Users()
    {
        $query = "SELECT FirstName, LastName, Points FROM Users JOIN TopTen
        WHERE Users.UserID = TopTen.UserID ORDER BY Points desc";     
        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
        {
            // Censor the email
            $rows[] = $temp;
        }
        return json_encode(array('TopTen' => $rows)); 
    }

    /**
     * Calculate the Top Ten Users.
     */
    public function calculateTop10Users()
    {
        //Delete everything from the top ten users table.
        $query = "Delete FROM TopTen";
        mysqli_query($this->con, $query);

        $query = "SELECT Ratings.UserID, count(Ratings.Rating) AS Points FROM 
        Ratings JOIN Users WHERE Users.UserID = Ratings.UserID GROUP BY Email
        ORDER BY Points DESC LIMIT 10";  
        $result = mysqli_query($this->con, $query);

        // Implode results to insert values in one query.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            array_push($rows, "(". $temp['UserID'] . ", " . $temp['Points'] . ")");
        $query = "INSERT INTO TopTen (UserID, Points) VALUES " . implode(',', $rows);
        mysqli_query($this->con, $query);
    }

    /**
     * Get the average ratings over time for a certain garage.
     * @param INT $parkingID The ID of the parking location.
     * @return JSON The average ratings for each hour that has a rating.
     */
    public function getAverage($parkingID)
    {
        //Query to get the average ratings for each hour there is a rating.
        $query = "SELECT HOUR(TIME(Timestamp)) AS Hour, avg(Rating) AS Rating
        FROM Ratings WHERE Ratings.ParkingID = '$parkingID' GROUP BY Hour ORDER BY Hour";
        $result = mysqli_query($this->con, $query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysqli_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('Ratings' => $rows)); 
    }
}
?>