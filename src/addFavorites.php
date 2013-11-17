<?
    include "phpapi.php";
    $parkingID = $_GET['parkingID'];
    $phpInit = new phpapi();
    echo($phpInit->addFavorites($parkingID));
    if($_GET['redirect'] != "false")
    header ('Location: editfav.php?stat=1');
	else
		header ('Location: garage.php?garageID='.$parkingID);
?>