(function() {
     var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
     po.src = 'https://apis.google.com/js/client:plusone.js';
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

function signinCallback(authResult)
{
    if (authResult['access_token'])
    {
        // Hide the sign-in button now that the user is authorized, for example:
        document.getElementById('signinButton').setAttribute('style', 'display: none');

        var email = "asdf";
        externalID_Google = "";
        fname_Google = "";
        lname_Google = "";
        // Get the email
        gapi.client.load('oauth2', 'v2', function()
        {
            gapi.client.oauth2.userinfo.get().execute(function(resp) {
                email = resp.email;
                alert("1: " + email);
            });
        });

        alert("2: " + email);
        
        // Get the id, first name, and last name
        gapi.client.load('plus', 'v1', function(externalID, fname, lname)
        {
            gapi.client.plus.people.get( {'userId' : 'me'} ).execute(function(resp) {
                externalID = resp.id;
                fname = resp.name.givenName;
                lname = resp.name.familyName;
                // console.log(resp.id);
                // console.log(resp.name.givenName);
                // console.log(resp.name.familyName);
            })
        });
        //alert(email.valueOf + " " + externalID.valueOf + " " + fname.valueOf + " " + lname.valueOf);
        // $.ajax({
        //     type: "POST",
        //     url: "checkGoogleUser.php",
        //     data: { "email":email, "externalID":externalID, "fname":fname, "lname":lname },
        // });
    }
    else if (authResult['error'])
    {
        // Update the app to reflect a signed out user
        $.ajax({ url: "signOut.php" });
    }
}