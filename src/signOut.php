<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>
    var token = gapi.auth.getToken();
    if (token)
    {
        var revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' +
            token.access_token;
        $.ajax({ url: revokeUrl });
    }
</script>
<?

include 'phpapi.php';



$phpInit = new phpapi();
$phpInit->signOut();






?>
