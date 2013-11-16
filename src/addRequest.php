<?
    include "phpapi.php";
    $phpInit = new phpapi();
    echo($phpInit->addRequest());
    header ('Location: request.php?stat=1');
?>