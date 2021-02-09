<?php

include_once "config.php";

$email = $username = $password = $confirm_password = "";
$email_err = $usernam_err = $password_err = $confirm_password_err = "";

?>

<!-- TODO style with bootstrap -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>login</title>
</head>
<body>
    <div class="header">
        <h3>please create an account</h3>
    </div>
    <div class="login">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" accept-charset="UTF-8">
            <fieldset>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>"><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>"><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>"><br>

                <label for="confirm_password">Confirm password:</label>
                <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>"><br>

                <input type="submit" value="Create">
                <a href="index.php">Back</a>
            </fieldset>
        </form>
    </div>

</body>
</html>