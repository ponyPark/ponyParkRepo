<?
    include "phpapi.php";
    $parkingID = $_GET['parkingID'];
    $phpInit = new phpapi();
    echo($phpInit->addRating($parkingID));
?>