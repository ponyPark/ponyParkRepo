<?
    include "phpapi.php";
    $favoriteID = $_GET['favoriteID'];
    $phpInit = new phpapi();
    echo($phpInit->deleteFavorites($favoriteID));
    header ('Location: editfav.php?stat=1');
?>