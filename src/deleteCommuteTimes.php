<?
    include "phpapi.php";
    $commuteID = $_GET['commuteID'];
    $phpInit = new phpapi();
    echo($phpInit->deleteCommuteTimes($commuteID));
?>