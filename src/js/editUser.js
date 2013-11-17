/*BAM Software JS for PonyPark
October, 2013*/

jQuery(document).ready(function() {
    // Populate the edit form with the user's profile
    $.ajax({
        dataType: "json",
        url: "getUser.php",
        success: function(user) {
            $( "input[name='fname']" ).val(user.UserInfo.FirstName);
            $( "input[name='lname']" ).val(user.UserInfo.LastName);
            $( "input[name='email']" ).val(user.UserInfo.Email);
            $( "input[name='phone']" ).val(user.UserInfo.PhoneNumber);
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
                if (output === "1")
                {
                    $("#signupResult").html("Your profile was successfully edited.");
                }
                else
                {
                    $("#signupResult").html("Oops, something went wrong. Try again.");
                }
            }
        });
    });
});