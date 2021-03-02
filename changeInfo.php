<?php 

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once("config.php");

$new_email = $new_username = "";
$new_email_err = $new_username_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["new_email"])) && empty(trim($_POST["new_username"]))) {
        $new_email_err = $new_username_err = "Please enter a new username or email";
    } else {
        if (!empty(trim($_POST["new_email"])) && preg_match('~@~', $_POST["new_email"]) !== 1) {
            $new_email_err = "Please enter a valid email";
        } else {
            $new_email = $_POST["new_email"]; // no matter empty or not, checking whether it's empty or not later
        }

        if (!empty(trim($_POST["new_username"])) && preg_match('~@~', $_POST["new_username"]) === 1) {
            $new_password_err = "Your username cannot contain the charcter '@'";
        } else {
            $new_username = $_POST["new_username"]; // same as above
        }
    }

    if (empty($new_email_err) && empty($new_username_err)) {

        $sql = "";
        if (empty($new_email)) {
            $sql = "UPDATE users SET email = ? WHERE id = ?";
        } elseif (empty($new_username)) {
            $sql = "UPDATE users SET username = ? WHERE id = ?";
        }

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param, $param_id);

            $param = (!empty($new_username) ? $new_username : $new_email);
            $param_id = $_SESSION["id"];

            if (mysqli_stmt_execute($stmt)) {
                session_destroy();
                header("location: login.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
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
<div class="wrapper">
    <h3>Change password</h3>
    <p>Please fill out the appropriate fields to change your password or email. Leave rest blank</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($new_email_err)) ? 'has-error' : ''; ?>">
            <label for="new_email">New email</label>
            <input type="text" name="new_email" id="new_email" class="form-control" value="<?php echo $new_email; ?>">
            <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($new_username_err)) ? 'has-error' : ''; ?>">
            <label for="new_username">New username</label>
            <input type="text" name="new_username" id="new_username" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="submit">
            <a class="btn btn-link" href="index.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>