<?php
session_start();
$_SESSION['logged_user_name'] = null;
echo "logged out successfully";
echo "to login again click:";

?>
<a href="index.php">login again</a>
<?php session_destroy(); ?>