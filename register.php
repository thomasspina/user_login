<?php

require_once "config.php";

$email = $username = $password = $confirm_password = "";
$email_err = $usernam_err = $password_err = $confirm_password_err = "";

// checks if a certain identifier (username / email) is valid
function checkIdentifierValidity(&$var, &$err, $name, $link) { // & is for pointers
    if (empty(trim($_POST[$name]))) {
        $err = "Please enter a valid {$name}";

    // filter @ from usernames since use it to differentiate emails from username when login
    } elseif (preg_match('~@~', $_POST[$name]) === 1 && $name == "username") {
        $err = "Your username cannot contain the charcter '@'";
    
    // if email doesn't countain @ then it isn't an email
    } elseif (preg_match('~@~', $_POST[$name]) !== 1 && $name == "email") {
        $err = "Please enter a valid email";
    } else {
        $sql = "SELECT id FROM users WHERE {$name} = ?"; // ? is a placeholder
        
        if ($stmt = mysqli_prepare($link, $sql)) { // prepares a statement to use on db, returns false if error
            mysqli_stmt_bind_param($stmt, "s", $param); // binds parameters to the prepared statement ("s" means that variable is type string)

            $param = trim($_POST[$name]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt); // stores results client side

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $err = "This {$name} is already in use by another account";
                } else {
                    $var = trim($_POST[$name]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    checkIdentifierValidity($email, $email_err, "email", $link);
    checkIdentifierValidity($username, $usernam_err, "username", $link);

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Your password must have at least 6 characters";

    // if password contains only numbers or if it contains no numbers
    } elseif (is_numeric(trim($_POST["password"])) || 1 !== preg_match('~[0-9]~', trim($_POST["password"]))) {
        $password_err = "Your password must contain both letters and numbers";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($confirm_password != $password)) {
            $confirm_password_err = "Password did not match";
        }
    }

    if (empty($usernam_err) &&  empty($password_err) &&  empty($email_err) &&  empty($confirm_password_err)) {
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
                echo "Something went wrong. Please try again later";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>register</title>
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
        <h3>Register</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" accept-charset="UTF-8">
            <fieldset>

                <div class="form-group <?php echo (!empty($email_err) ? 'has-error' : ''); ?>">
                    <label for="email">Email:</label>
                    <input class="form-control" type="text" id="email" name="email" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                

                <div class="form-group <?php echo (!empty($usernam_err) ? 'has-error' : ''); ?>">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username" name="username" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $usernam_err; ?></span>
                </div>
                
                <div class="form-group <?php echo (!empty($password_err) ? 'has-error' : ''); ?>">
                    <label for="password">Password:</label>
                    <input class="form-control" type="password" name="password" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                
                <div class="form-group <?php echo (!empty($confirm_password_err) ? 'has-error' : ''); ?>">
                    <label for="confirm_password">Confirm password:</label>
                    <input class="form-control" type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
            
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Create">
                    <input type="reset" class="btn btn-default" value="Reset form"> 
                </div>
                <a href="login.php">I already have an account</a>
            </fieldset>
        </form>
    </div>
</body>
</html>