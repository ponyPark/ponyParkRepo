<? 

include 'phpapi.php';



$phpInit = new phpapi();
$phpInit->addUser();
$phpInit->verifyUserAndroid();
$phpInit = new phpapi();
if (!$phpInit->addUser())
{
    echo('ERROR');
}
else
{
    $phpInit->verifyUserAndroid();
}

?>
