<? 

include 'phpapi.php';
$phpInit = new phpapi();

// Stop processing the user add request if the user failed the captcha test.
// **Uncomment the line below to enable captcha processing.**

//if (!$phpInit->checkCaptcha()) die("captcha_failure");

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
