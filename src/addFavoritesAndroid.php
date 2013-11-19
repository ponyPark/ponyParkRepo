<?
    include "phpapi.php";
    $userID = $_GET['userID'];
    $parkingID = $_GET['parkingID'];
    $phpInit = new phpapi();
    echo($phpInit->addFavoritesAndroid($userID, $parkingID));
?>