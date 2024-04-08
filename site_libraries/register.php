<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - City Libraries Network</title>
    <link rel="stylesheet" href="/css/stylesheet.css">
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>
        <form action="/auth/register_process.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a class="login-link" href="login.php">Login</a></p>
    </div>
</body>
</html>
