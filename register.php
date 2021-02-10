<?php

include_once "config.php";

$email = $username = $password = $confirm_password = "";
$email_err = $usernam_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?"; 
        
        if ($stmt = mysqli_prepare($link, $sql)) { 
            mysqli_stmt_bind_param($stmt, "s", $param_email); 

            $param_email = trim($_POST["email"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "There already exists an account with that email.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    // TODO find a way to combine the statement below with the one above. Make a function or something. Seems redundant
    if (empty(trim($_POST["username"]))) {
        $usernam_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?"; 
        
        if ($stmt = mysqli_prepare($link, $sql)) { 
            mysqli_stmt_bind_param($stmt, "s", $param_username); 

            $param_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $usernam_err = "There already exists an account with that username.";
                } else {
                    $usernam_err = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>register</title>
</head>
<body>
    <div class="header">
        <h3>please create an account</h3>
    </div>
    <div class="register">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" accept-charset="UTF-8">
            <fieldset>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>"><br>
                <span><?php echo $email_err; ?></span>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>"><br>
                <span><?php echo $usernam_err; ?></span>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>"><br>
                <span><?php echo $password_err; ?></span>

                <label for="confirm_password">Confirm password:</label>
                <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>"><br>
                <span><?php echo $confirm_password_err; ?></span>

                <input type="submit" value="Create">
                <a href="index.php">Back</a>
            </fieldset>
        </form>
    </div>
</body>
</html>