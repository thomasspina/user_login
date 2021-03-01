<?php 

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

require_once "config.php";

$identifier = $password = "";
$identifier_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["identifier"]))) {
        $identifier_err = "Please enter a username or an email";
    } else {
        $identifier = trim($_POST["identifier"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password";
    } else {
        $password = trim($_POST["password"]);
    }

    // validate creds
    if (empty($identifier_err) && empty($password_err)) {
        $sql = "";

        if (preg_match('~@~', $_POST["identifier"]) === 1) {
            $sql = "SELECT id, email, username, password FROM users WHERE email = ?";
        } else {
            $sql = "SELECT id, email, username, password FROM users WHERE username = ?";
        }

        if ($stmt = mysqli_prepare($link,$sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_identifier);

            $param_identifier = $identifier;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                // verify the user exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $username, $hashed_password);
                
                    // fetches the variables that were bound
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;

                            header("location: index.php");
                        } else {
                            $identifier_err = "The username / email or password you entered was not valid";
                            $password_err = "The username / email or password you entered was not valid";
                        }
                    } else {
                        $identifier_err = "The username / email or password you entered was not valid";
                        $password_err = "The username / email or password you entered was not valid";
                    }
                } else {
                    $identifier_err = "The username / email or password you entered was not valid";
                    $password_err = "The username / email or password you entered was not valid";
                }

                mysqli_stmt_close($stmt);

            } else {
                echo "Oops! Something went wrong. Please try again later";
            }
        }
    }
    
    mysqli_close($link);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
            crossorigin="anonymous">

    <style type="text/css">
        body{ font: 14px sans-serif }
        .wrapper{ width: 500px; padding: 40px;  }
    </style>
</head>
<body>

    <div class="wrapper">
        <h3>Login</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" accept-charset="UTF-8">
            <div class="form-group <?php echo (!empty($identifier_err)) ? 'has-error' : ''; ?>">
                <label for="id">Username or email:</label>
                <input class="form-control" type="text" id="identifier" name="identifier">
                <span class="help-block"><?php echo $identifier_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label for="password">Password:</label>
                <input class="form-control" type="password" id="password" name="password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Login">
            </div>
            <a href="register.php">I don't have an account</a>
        </form>
    </div>
</body>
</html>