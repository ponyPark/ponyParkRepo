/*BAM Software JS for PonyPark
October, 2013*/

jQuery(document).ready(function() {
    $("#createAccount").submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: "create.php",
            data: $(this).serialize(),
            beforeSend: function() { $("#signupResult").html("Processing, please wait..."); },
            success: function(output) {
                if (output === "signup_failure")
                {
                    $("#signupResult").html("That email is already in use.");
                }
                else if (output === "login_success")
                {
                    window.location.replace("index.php");
                }
                else
                {
                    window.location.replace("signup.php?login=false");
                }
            }
        });
    });
});