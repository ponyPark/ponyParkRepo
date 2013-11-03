<?
    include "phpapi.php";
    $parkingID = $_GET['parkingID'];
    $phpInit = new phpapi();
    $phpInit->addRating($parkingID);
    header('Location: index.php');
?>