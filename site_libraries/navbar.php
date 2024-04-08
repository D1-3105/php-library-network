<?php
// Проверяем существование сессии пользователя
$is_logged_in = isset($_SESSION['user_id']);
// Проверяем, залогинен ли пользователь
if ($is_logged_in) {
    // Если пользователь залогинен, отображаем кнопку профиля и скрываем кнопки входа и регистрации
    $profile_block = '<a class="nav-link" href="/profile.php"><img src="/static/profile_icon.svg" alt="Profile" height="30"></a>';
    echo $profile_block;
} else {
    // Если пользователь не залогинен, отображаем кнопки входа и регистрации
    $login_block = '<li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li>';
    $register_block = '<li class="nav-item"><a class="nav-link" href="/register.php">Register</a></li>';
    echo $login_block;
    echo $register_block;
}
?>
