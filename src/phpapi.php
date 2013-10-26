<?
/** 
 * Designed by BAM Software.
 * This file contains the api for all our php functions.
 * There are other php files that access these functions separately.
 */
class phpapi
{
    /**
     * The constructor for our php api. Opens a connection to the mySQL
     * database. 
     */
    function phpapi()
    {
        session_start();

        $con = mysql_connect("localhost", "ponypark", "ponypark");
        if(!$con)
            die('Could not connect: ' . mysql_error());
        mysql_select_db("PonyPark", $con)
        or die("Unable to select database: " . mysql_error());
    }

    /**
     * The function to add a user into the database using the information
     * sent through the POST. Using queries to insert into the table.
     */
    public function addUser()
    {   
        //add a user to the system
        $mailList = $_POST['mailingList'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $pw = hash(md5, $_POST['pw']);
        $phone = $_POST['phone'];
        $username = $fname. ' ' .$lname;

        $auth = 0;
        $query = "INSERT INTO Users(Username,Email,
            Password,PhoneNumber,UserType) VALUES 
            ('$username','$email','$pw','$phone','$auth')";
        if(!mysql_query($query))
        {
            return false;
        }
        else
        {
            return; 
        }
    
    }

    /**
     * A function to verify that a user is entering the right information when
     * logging in. By retrieving information from a query to the database. It 
     * also saves the information into a session. If a user is an employee, they
     * are sent to the analytics page.
     */
    public function verifyUser()
    {
        //verify a user and start a new session
        $pw = hash(md5, $_POST['pw']);
        $query = "select * from Users where Email = '";
        $query = $query . $_POST['email'] . "' and Password = '" . $pw ."'";
        $result = mysql_query($query);
        if(mysql_num_rows($result) == 0)
            header ('Location: index.php?login=false');

        $info = mysql_fetch_array( $result );

        if(mysql_num_rows($result) > 0)
        {
            $_SESSION['logged'] = true;
            $_SESSION['userEmail'] = $info['Email'];
            //Added the user to the session since we use
            //that for adding favorites, etc.
            $_SESSION['userID'] = $info['UserID'];
            $_SESSION['userName'] = $info['Username'];
            header ('Location: index.php'); 
        }

        
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
        $_SESSION = array();
        session_destroy();
        header ('Location: index.php');
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
        $query = "SELECT *, (SELECT Ratings.Rating FROM Ratings WHERE 
            ParkingLocations.ParkingID = Ratings.ParkingID ORDER BY Timestamp 
            desc limit 1) Rating FROM ParkingLocations WHERE 
            ParkingLocations.parkingID = '$parkingID'";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = mysql_fetch_assoc($result);
        return json_encode(array('ParkingInfo' => $rows));
    }

    /**
     * A function to get the list of parking locations for map and list view. 
     * @return JSON A list of the different locations and their ratings.
     */
    public function getParkingLocations()
    {
        
        //Get the most recent Rating, Name, and Address for every garage.
        $query = "SELECT ParkingLocations.Name, ParkingLocations.Address,
            (SELECT Ratings.Rating FROM Ratings WHERE ParkingLocations.ParkingID
            = Ratings.ParkingID ORDER BY Timestamp desc limit 1) Rating FROM
            ParkingLocations ORDER BY ParkingLocations.Name";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('ParkingLocations' => $rows));

    }

    /**
     * A function to add a rating to the database.
     * @param INT $parkingID The ID of the parking location.
     */
    public function addRating($parkingID)
    {
        $ratingInfoJSON = $_POST['ratingInfo'];
        if (empty($ratingInfoJSON)) return false;

        // Retrieve the values from the session and the post.
        $userID = $_SESSION['userID'];

        // Read the JSON.
        $ratingInfo = (array) json_decode($ratingInfoJSON);

        $query = "INSERT INTO Ratings (ParkingID, Level, Timestamp, UserID, 
            Rating) VALUES ('$parkingID', '" . $ratingInfo['level'] . 
            "', NOW(), '$userID', '" . $ratingInfo['rating'] . "')";
        $result = mysql_query($query);
    }

     /**
     * A function to add a request for a parking garage.
     */
    public function addRequest()
    {
        $requestInfoJSON = $_POST['requestInfo'];
        if (empty($requestInfoJSON)) return false;

        // Retrieve the UserID.
        $userID = $_SESSION['userID'];

        // Read the JSON.
        $requestInfo = (array) json_decode($requestInfoJSON);

        //Change comments and cost if NULL
        $cost = empty($requestInfo['cost']) ? "NULL" : "'" . $requestInfo['cost'] . "'";
        $comments = empty($requestInfo['commments']) ? "NULL" : "'" .
            mysql_real_escape_string($requestInfo['comments']) . "'";
        
        //Query to insert request into the table
        $query = "INSERT INTO Requests (UserID, Name, Address, Cost, 
            NumberOfLevels, Comments, Status) VALUES ('$userID', '" . 
            mysql_real_escape_string($requestInfo['name']) . "','" .
            mysql_real_escape_string($requestInfo['address']) . "', $cost,'" . 
            $requestInfo['numLevels'] . "', $comments, 0)"; 
        $result = mysql_query($query);
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
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
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
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
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
        mysql_query($query);

        if($status == 2)
        {
            $query = "INSERT INTO ParkingLocations (Name, Address, Cost, 
                Comments, NumberOfLevels) SELECT Name, Address, Cost, 
                Comments, NumberOfLevels FROM Requests WHERE RequestID = 
                $requestID";
            mysql_query($query);
        }
    }
}
?>