<?
    include "phpapi.php";
    $favoriteID = $_GET['favoriteID'];
    $parkingID = $_GET['parkingID'];
    $phpInit = new phpapi();
    echo($phpInit->deleteFavorites($favoriteID));
    if($_GET['redirect'] != "false")
    header ('Location: editfav.php?stat=1');
	else
		header ('Location: garage.php?garageID='.$parkingID);
?>