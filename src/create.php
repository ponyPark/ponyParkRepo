<? 

include 'phpapi.php';



$phpInit = new phpapi();

if (!$phpInit->addUser())
{
    echo "signup_failure";
}
else
{
    if ($phpInit->verifyUser())
        echo "login_success";
    else
        echo "login_failure";
}

?>
