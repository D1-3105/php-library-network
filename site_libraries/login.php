<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - City Libraries Network</title>
    <link rel="stylesheet" href="/css/stylesheet.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="/auth/login_process.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a class="register-link" href="register.php">Register</a></p>
    </div>
</body>
</html>