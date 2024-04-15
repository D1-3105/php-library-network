    <div class="login-container">
        <h2>Вход</h2>
        <form action="/auth/login_process.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Войти</button>
        </form>
        <p>Нет аккаунта? <a class="register-link" href="/register/">Зарегистрироваться</a></p>
    </div>