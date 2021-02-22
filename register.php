<?php

require_once "config.php";

$email = $username = $password = $confirm_password = "";
$email_err = $usernam_err = $password_err = $confirm_password_err = "";

// checks if a certain identifier (username / email) is valid
function checkIdentifierValidity($var, $err, $name, $link) {
    if (empty(trim($_POST[$name]))) {
        $err = "Please enter a valid input.";

    // filter @ from usernames since use it to differentiate emails from username when login
    } elseif (preg_match('~@~', $_POST[$name]) === 1 && $name == "email") {
        $err = "Your username cannot contain an @ character.";
    } else {
        $sql = "SELECT id FROM users WHERE {$name} = ?"; // ? is a placeholder
        
        if ($stmt = mysqli_prepare($link, $sql)) { // prepares a statement to use on db, returns false if error
            mysqli_stmt_bind_param($stmt, "s", $param); // binds parameters to the prepared statement

            $param = trim($_POST[$name]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt); // stores results client side

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $err = "This {$name} is already in use by another account.";
                } else {
                    $var = trim($_POST[$name]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    checkIdentifierValidity($email, $email_err, "email", $link);
    checkIdentifierValidity($username, $usernam_err, "username", $link);

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Your password must have at least 6 characters.";

    // if password contains only numbers or if it contains no numbers
    } elseif (is_numeric(trim($_POST["password"])) || 1 !== preg_match('~[0-9]~', trim($_POST["password"]))) {
        $password_err = "Your password must contain both letters and numbers.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($confirm_password != $password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_username, $param_password);

        $param_email = $email;
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // redirects on execute success
        if (mysqli_stmt_execute($stmt)) {
            header("location: login.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
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