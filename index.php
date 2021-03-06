<?php 

session_start();

// redirects to login if not already
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
            crossorigin="anonymous">

    <style type="text/css">
        body{ font: 16px sans-serif; }
        .wrapper{ width: 500px; padding: 40px; }
    </style>
</head>

<body>
    <div class="wrapper">
        <div>
            <h3>Hello <?php echo $_SESSION["username"]?></h3><br>
            <p>Welcome to your very own homepage.</p>
        </div>
        

        <div>
            <p>Change account <a href="changeInfo.php">information</a> or <a href="changePassword.php">password</a></p><br>
            <a href="logout.php">Sign out</a>
        </div>
    </div>
</body>

</html>