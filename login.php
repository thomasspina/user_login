<?php 

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

require_once "config.php";

$identifier = $password = "";
$identifier_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["identifier"]))) {
        $identifier_err = "Please enter a username or an email.";
    } else {
        $identifier = trim($_POST["identifier"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // validate creds
    if (empty($identifier_err) && empty($password_err)) {
        $sql = "";

        if (preg_match('@', $_POST["identifier"]) === 1) {
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
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password);
                
                    // fetches bound variables
                    if (mysqli_stmt_fetch($stmt)) {

                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;

                            header("location: home.php");
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    } else {
                        $identifier_err = "There is no account with that username or email.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
    
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>login</title>
</head>
<body>
    <div class="header">
        <h3>please login</h3>
    </div>
    <div class="login">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" accept-charset="UTF-8">
            <fieldset>
                <label for="id">Username or email:</label>
                <input type="text" id="identifier" name="identifier"><br>
                <label for="password">Password:</label>
                <input type="text" id="password" name="password"><br>
                <input type="submit" value="Login">
                <a href="createAccount.php">I don't have an account</a>
            </fieldset>
        </form>
    </div>

</body>

</html>