/*BAM Software JS for PonyPark
October, 2013*/

jQuery(document).ready(function() {
    // Populate the page with the user's profile
    $.ajax({
        dataType: "json",
        url: "getUser.php",
        success: function(user) {
            if (user.UserInfo.UserType == 1)
                $( "#userType" ).html("Admin");
            else
                $( "#userType" ).html("Regular Member");
            $( "#accountType" ).html(user.UserInfo.ExternalType);
            $( "#firstName" ).html(user.UserInfo.FirstName);
            $( "#lastName" ).html(user.UserInfo.LastName);
            $( "#email" ).html(user.UserInfo.Email);
            if (user.UserInfo.PhoneNumber)
                $( "#phoneNumber" ).html(user.UserInfo.PhoneNumber);
            else
                $( "#phoneNumber" ).html("Not Available");
        }
    });
});