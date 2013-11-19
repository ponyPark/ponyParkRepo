<?
    include "phpapi.php";
    $userID = $_GET['userID'];
    $phpInit = new phpapi();
    echo($phpInit->getFavoritesAndroid($userID));
?>