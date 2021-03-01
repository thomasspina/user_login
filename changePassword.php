<?php 

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once("config.php");

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

}
?>

<!doctype hmtl>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>change password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
            crossorigin="anonymous">

    <style type="text/css">
        body{ font: 16px sans-serif; }
        .wrapper{ width: 500px; padding: 40px; }
    </style>
</head>
<body>


</body>
</html>