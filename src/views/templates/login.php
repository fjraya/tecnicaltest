<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

</head>
<body>
<div id="main">
    <h1>Login</h1>

    <div id="login">
        <h2>Login Form</h2>

        <form action="/login" method="post">
            <?php echo $errorMsg ?><br />
            <input type="hidden" name="uri" value = "<?php echo $uri?>" />
            <label>UserName :</label>
            <input id="name" name="username" placeholder="username" type="text"><br />
            <label>Password :</label>
            <input id="password" name="password" placeholder="**********" type="password">
            <input name="submit" type="submit" value=" Login ">
        </form>
    </div>
</div>
</body>
</html>