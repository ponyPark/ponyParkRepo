Welcome back to PonyPark, <? session_start(); echo($_SESSION['userName']);?>!
<ol id="userList">
    <li><a href="maccount.php">Manage Account</a></li>
    <li><a href="favlist.php">Favorite List</a></li>
    <li><a href="#" id="signOut">Sign Out</a></li>
</ol>

<script>
    $("#signOut").click(disconnectUser);
</script>