<?
    include "phpapi.php";
    $requestID = $_GET['requestID'];
    $status = $_GET['status'];
    $phpInit = new phpapi();
    echo($phpInit->editRequests($requestID, $status));
?>