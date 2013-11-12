(function() {
     var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
     po.src = 'https://apis.google.com/js/client:plusone.js';
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

function getEmail(access_token)
{
    return $.ajax({
        type: "GET",
        url: "https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token="+access_token
    });
}

function signinCallback(authResult)
{
    if (authResult['access_token'])
    {
        // Hide the sign-in button now that the user is authorized, for example:
        //document.getElementById('signinButton').setAttribute('style', 'display: none');

        var email, externalID, fname, lname;

        // Get the email, id, first name, and last name
        gapi.client.load('plus', 'v1', function()
        {
            gapi.client.plus.people.get( {'userId' : 'me'} ).execute(function(resp) {
                externalID = resp.id;
                fname = resp.name.givenName;
                lname = resp.name.familyName;
                getEmail(authResult['access_token']).done(function(result) {
                    email = result.email;
                    $.ajax({
                        type: "POST",
                        url: "checkGoogleUser.php",
                        data: { "email":email, "externalID":externalID, 
                            "fname":fname, "lname":lname },
                        success: function(result) {
                            if (result === "1")
                                window.location.replace("index.php");
                        }
                    });
                });
            })
        });
    }
    // else if (authResult['error'])
    // {
    //     // Update the app to reflect a signed out user
    //     $.ajax({ url: "signOut.php" });
    // }
}

function disconnectUser()
{
    var token = gapi.auth.getToken();
    if (token)
    {
        var revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' +
            token.access_token;
        $.ajax({
            type: 'GET',
            url: revokeUrl,
            async: false,
            contentType: "application/json",
            dataType: 'jsonp',
            success: function() {
                $.ajax({
                    url: "signOut.php",
                    success: function(result) {
                        if (result === "1")
                            window.location.replace("index.php");
                    }
                });
            }
        });
    }
    else
    {
        $.ajax({
            url: "signOut.php",
            success: function(result) {
                if (result === "1")
                    window.location.replace("index.php");
            }
        });
    }
}

//jQuery(document).ready(function() {
//    $("#signOut").click(disconnectUser);
//});