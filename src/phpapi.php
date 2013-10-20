<?
/** 
 * Designed by BAM Software.
 * This file contains the api for all our php functions.
 * There are other php files that access these functions separately.
 * PONYPARK FALL 2013
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
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $auth = 0;
        $query = "INSERT INTO Users(MailingList,FirstName,LastName,Email,
            Password,PhoneNumber,Address,City,State,ZipCode,Privilege) VALUES 
            ('$mailList','$fname','$lname','$email','$pw','$phone','$address',
            '$city','$state','$zip','$auth')";
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

        $ipaddress = substr((string)$_SERVER['REMOTE_ADDR'], 0,7);
        
        if($info['Privilege'] == 1 && $ipaddress == "129.119")
        {
            header ('Location: analytics.php');
        }
        else if(mysql_num_rows($result) > 0)
        {
            $_SESSION['logged'] = true;
            $_SESSION['userEmail'] = $info['Email'];
            //Added the user to the session since we use
            //that for adding favorites, etc.
            $_SESSION['userID'] = $info['UserID'];
            header ('Location: order.php'); 
        }

        
    }
    
    /**
     * A function to add a favorite cupcake to a certain user's database with two
     * queries, using information from the SESSION and POST.
     */
    public function addFavorites()
    {
        // Retrieve the values from the session and the post. 
        $userID = $_SESSION['userID'];
        $filling = $_POST['fillingID'];
        $frosting = $_POST['frostingID'];
        $cake = $_POST['CakeID'];
        // Fail-safe in case someone tries to hack us.
        $favName = mysql_real_escape_string($_POST['favoriteName']);
        $toppingsList = json_decode($_POST['toppingsID']);

        // Gets the current id in the favorites table.
        $favoriteStatus = mysql_fetch_assoc(mysql_query("SHOW TABLE STATUS LIKE 'Favorites'"));
        $favoriteID = $favoriteStatus['Auto_increment'];

        // Query to insert a favorite.
        $query = "INSERT INTO Favorites(UserID, CakeID, FillingID, FrostingID, 
            Name) VALUES ('$userID', '$cake', '$filling', '$frosting', '$favName')";
        if(!mysql_query($query))
            return false;
        
        if(count($toppingsList)!=0)
        {
            // Changes all values in toppingsList to (favoiteID, value) where 
            // value is a specific topping    
            foreach ($toppingsList as &$value)
                $value = "('$favoriteID','$value')";

            // Query to insert all toppings into FavoriteToppings 
            // (with magical implode to concatenate all (favoriteID, value)'s 
            // from toppingsList with a comma.           
            $query = "INSERT INTO FavoriteToppings(FavoriteID, ToppingsID) 
                VALUES " . implode(",", $toppingsList);
            if(!mysql_query($query))
                return false;
        }            
        return true;
    }
        
    /**
     * A function to get the favorites of a certain user.
     * @return JSON The list of favorite cucpakes.
     */    
    public function getFavorites()
    {
        // Getting the name, id, and image of a user favorites using their id.
        if(!isset($_SESSION['userID']))
            return false;
        $userID = $_SESSION['userID'];

        // Query to get the favorites of a user.
        $query = "SELECT FavoriteID, Name, Img_url FROM Favorites JOIN Cakes 
            ON Favorites.CakeID = Cakes.CakeID WHERE UserID = '$userID'";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('favorites' => $rows));
    }
    
    /**
     * A function to get the list of cakes. 
     * @return JSON A list of the different kinds of cakes.
     */
	public function getCakes()
	{
        // Get all the cakes.
        $query = "SELECT * FROM Cakes";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('cakes' => $rows));

    }

    /**
     * A function to get the list of fillings. 
     * @return JSON A list of the different kinds of fillings.
     */
    public function getFillings()
    {
        // Get all the fillings.
        $query = "SELECT * FROM Fillings";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('fillings' => $rows));
    }

    /**
     * A function to get the list of frostings. 
     * @return JSON A list of the different kinds of frostings.
     */
    public function getFrostings()
    {
        // Get all the frostings.
        $query = "SELECT * FROM Frosting";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('frostings' => $rows));
    }

    /**
     * A function to get the list of toppings. 
     * @return JSON A list of the different kinds of toppings.
     */
    public function getToppings()
    {
        // Get all the toppings.
        $query = "SELECT * FROM Toppings";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('toppings' => $rows));   
    }

    /**
     * A function to get the cake for a certain favorite cupcake.
     * @param INT $favoriteID The favorite cucpake ID.
     * @return JSON The cakeID for a cupcake.
     */
    public function getFavoriteCake($favoriteID)
    {
        // Get the favorite cake based on the favorite ID.
        $query = "SELECT CakeID FROM Favorites WHERE FavoriteID = '$favoriteID'";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = mysql_fetch_assoc($result);
        return json_encode(array('favCake' => $rows));

    }

    /**
     * A function to get the filling for a certain favorite cupcake.
     * @param INT $favoriteID The favorite cucpake ID.
     * @return JSON The fillingID for a cupcake.
     */
    public function getFavoriteFilling($favoriteID)
    {
        // Get the favorite filling based on the favoriteID.
        $query = "SELECT FillingID FROM Favorites WHERE FavoriteID = '$favoriteID'";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = mysql_fetch_assoc($result); 
        return json_encode(array('favfilling' => $rows));
    }

    /**
     * A function to get the frosting for a certain favorite cupcake.
     * @param INT $favoriteID The favorite cucpake ID.
     * @return JSON The frostingID for a cupcake.
     */
    public function getFavoriteFrosting($favoriteID)
    {
        // Get the favorite filling based on the favoriteID.
        $query = "SELECT FrostingID FROM Favorites WHERE FavoriteID = '$favoriteID'";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = mysql_fetch_assoc($result); 
        return json_encode(array('favfrosting' => $rows));
    }

    /**
     * A function to get the toppings for a certain favorite cupcake.
     * @param INT $favoriteID The favorite cucpake ID.
     * @return JSON The list of toppingsID for a cupcake.
     */
    public function getFavoriteToppings($favoriteID)
    {
        // Get all the favorite toppings based on the favoriteID.
        $query = "SELECT ToppingsID FROM FavoriteToppings WHERE FavoriteID = '$favoriteID'";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('favtoppings' => $rows));   
    }

    /**
     * A function to add a cupcake to the CupcakeOrders and OrderToppings
     * table in mySQL. Retrieves a POST to get the order.
     */
    public function addOrder()
    {
        $orderJSON = $_POST['orderArray'];
        if (empty($orderJSON)) return false;

        // Retrieve the values from the session and the post. 
        $userID = $_SESSION['userID'];

        // Read the JSON.
        $order = (array) json_decode($orderJSON);
        echo($order['']);

        // Get the next order id.
        $orderStatus = mysql_fetch_assoc(mysql_query("SHOW TABLE STATUS LIKE 'CupcakeOrders'"));
        $orderID = $orderStatus['Auto_increment'];

        // Make a query to add the order, excluding the toppings.
        $query = "INSERT INTO CupcakeOrders(UserID, Quantity, CakeID, FillingID,
            FrostingID) VALUES('$userID','" . $order['quantity'] . "','" .
            $order['flavor'] . "','" . $order['filling'] . "','" . 
            $order['frosting'] . "')";
        if(!mysql_query($query))
            return false;

        $toppingsList = (array) $order['toppings'];
        if(count($toppingsList)!=0)
        {
            // Changes all values in the toppings array to (orderID, value) 
            // where value is a specific topping    
            foreach ($toppingsList as &$value)
                $value = "('$orderID','$value')";

            // Query to insert all toppings into OrderToppings 
            // (with magical implode to concatenate all (orderID, value)'s from 
            // toppingsList with a comma.           
            $query = "INSERT INTO OrderToppings(OrderID, ToppingsID) VALUES " . 
                implode(",", $toppingsList);

            if(!mysql_query($query))
                return false;
        }                  
        return true;
 
    }

    /**
     * A function to get the cake sales information from CupcakeOrders.
     * @return JSON The list of cakes flavors and the quantity ordered.
     */
    public function getCakeSalesInformation()
    {
        // Get the query for cake sales info.
        $query = "SELECT Flavor, sum(Quantity) Total FROM CupcakeOrders 
            NATURAL JOIN Cakes GROUP BY Flavor ORDER BY Flavor";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('cakesales' => $rows));   
    }

    /**
     * A function to get the filling sales information from CupcakeOrders.
     * @return JSON The list of filling flavors and the quantity ordered.
     */
    public function getFillingSalesInformation()
    {
        // Get the query for filling sales info.
        $query = "SELECT Flavor, sum(Quantity) Total FROM CupcakeOrders 
            NATURAL JOIN Fillings GROUP BY Flavor ORDER BY Flavor";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('fillingsales' => $rows));   
    }

    /**
     * A function to get the frosting sales information from CupcakeOrders.
     * @return JSON The list of frosting flavors and the quantity ordered.
     */
    public function getFrostingSalesInformation()
    {
        // Get the query for frosting sales info.
        $query = "SELECT Flavor, sum(Quantity) Total FROM CupcakeOrders 
            NATURAL JOIN Frosting GROUP BY Flavor ORDER BY Flavor";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('frostingsales' => $rows));   


    }

    /**
     * A function to get the toppings sales information from CupcakeOrders and
     * OrderToppings.
     * @return JSON The list of toppings and the quantity ordered.
     */
    public function getToppingsSalesInformation()
    {
        // Get the query for topping sales info.
        $query = "SELECT Flavor, sum(Quantity) Total FROM OrderToppings 
            NATURAL JOIN Toppings NATURAL JOIN CupcakeOrders GROUP BY Flavor 
            ORDER BY Flavor";
        $result = mysql_query($query);

        // Change mysql result to array so that it can be exported in JSON.
        $rows = array();
        while($temp = mysql_fetch_assoc($result))
            $rows[] = $temp;
        return json_encode(array('toppingsales' => $rows));
    }
}
?>