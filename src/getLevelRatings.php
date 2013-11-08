<?
    include "phpapi.php";
    $parkingID = $_GET['parkingID'];
    $level = $_GET['level'];
    $phpInit = new phpapi();
    echo($phpInit->getLevelRatings($parkingID, $level));
?>