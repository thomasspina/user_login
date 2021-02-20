<?php 

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}

require_once "config.php";


// TODO in the register.php page. Username cannot have @ in them to differentiate between email and username
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