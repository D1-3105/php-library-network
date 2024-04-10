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
        <h2>Регистрация</h2>
        <form action="/auth/register_process.php" method="post">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p>Уже с нами? <a class="login-link" href="login.php">Войти</a></p>
    </div>
</body>
</html>
