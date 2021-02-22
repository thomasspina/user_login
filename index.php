<?php 

session_start();

// redirects to login if not already
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
</head>

<body>
    <div class="content">
    <p>hello [username]</p> 
    <a href="logout.php">Sign Out of Your Account</a>
    </div>
</body>

</html>