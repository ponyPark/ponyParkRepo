<script>
window.fbAsyncInit = function() {
  FB.init({
    appId      : '553041308102464', // App ID
    channelUrl : '//ponypark.floccul.us/channel.html', // Channel File
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

    FB.getLoginStatus(function(ret) {
        if(ret.authResponse) {
            FB.logout(function() {
            });
        }
    });
};

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));
</script>

<?
include 'phpapi.php';

$phpInit = new phpapi();
echo $phpInit->signOut();
?>