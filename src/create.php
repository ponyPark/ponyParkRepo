<? 

include 'phpapi.php';



$phpInit = new phpapi();
if (!$phpInit->addUser())
{
    header('Location: signup.php?error=true');
}
else
{
    $phpInit->verifyUser();
}


?>
