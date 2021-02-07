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
        <form action="login.php" method="post" accept-charset="UTF-8">
            <fieldset>
                <label for="id">Email:</label>
                <input type="text" id="id" name="id"><br>
                <label for="password">Password:</label>
                <input type="text" id="password" name="password"><br>
                <input type="submit" value="Login">
                <a href="createAccount.php">I don't have an account</a>
            </fieldset>
        </form>
    </div>

</body>

</html>