<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="author" content="Dakota Whitney" />
  <link rel="stylesheet" type="text/css" href="./styles.css" />
  <link rel="icon" type="image/x-icon" href="./terminal-icon.png">
  <title>Login - NBC OTS VIP CLI Web Console</title>
</head>
<body>
    <main id="login-form">
        <h1>NBC OTS VIP CLI Web Console</h1>
        <h2>Login</h2>
        <form method="post" action="./app.php">
            <label for="nbcu_email">NBCU Email: </label>
            <input type="email" name="nbcu_email" placeholder="@nbcuni.com"></input>
            <br />
            <label for="user_password">Password: </label>
            <input type="password" name="user_password"></input>
            <br />
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>