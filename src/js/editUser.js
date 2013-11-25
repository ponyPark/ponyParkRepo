/*BAM Software JS for PonyPark
October, 2013*/

jQuery(document).ready(function() {
    $( "#changePassword" ).hide();

    // Populate the edit form with the user's profile
    $.ajax({
        dataType: "json",
        url: "getUser.php",
        success: function(user) {
            $( "input[name='fname']" ).val(user.UserInfo.FirstName);
            $( "input[name='lname']" ).val(user.UserInfo.LastName);
            $( "input[name='phone']" ).val(user.UserInfo.PhoneNumber);

            if (user.UserInfo.ExternalType === "Native")
                $( "#changePassword" ).show();
        }
    });

    // When a user submits the edit form, process it and give the user feedback.
    $("#submitEditUser").submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: "editUser.php",
            data: $(this).serialize(),
            beforeSend: function() { $("#signupResult").html("Processing, please wait..."); },
            success: function(output) {
                if (output === "missing_info")
                {
                    $("#signupResult").html("First name and last name are required.");
                }
                else if (output === "wrong_password")
                {
                    $("#signupResult").html("The password confirmation failed.");
                    $( "input[name='old_pw']" ).val("");
                    $( "input[name='new_pw']" ).val("");
                }
                else
                {
                    $("#signupResult").html("Your profile was successfully edited.");
                    $( "input[name='old_pw']" ).val("");
                    $( "input[name='new_pw']" ).val("");
                }
            }
        });
    });
});