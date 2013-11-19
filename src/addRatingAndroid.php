<?
    include "phpapi.php";
    $userID = $_GET['userID'];
    $parkingID = $_GET['parkingID'];
    $phpInit = new phpapi();
    $phpInit->addRatingAndroid($userID, $parkingID);
?>