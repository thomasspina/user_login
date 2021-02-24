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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
            crossorigin="anonymous">

    <style type="text/css">
        body{ font: 16px sans-serif; }
    </style>
</head>

<body>
    <div class="content">
    <p>hello <?php echo $_SESSION["username"]?></p> 
    <a href="logout.php">Sign Out of Your Account</a>
    </div>
</body>

</html>