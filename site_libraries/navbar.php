<?php
// Проверяем существование сессии пользователя
$is_logged_in = isset($_SESSION['user_id']);
$url = $_SERVER['REQUEST_URI'];
$user_id = $is_logged_in?$_SESSION['user_id']:null;

if ($is_logged_in && strpos($url, '/profile') === false) {
    // Если пользователь залогинен и не находится на странице профиля, отображаем кнопку профиля
    $profile_block = '<a class="nav-link" href="/profile/' . $user_id . '"><img src="/static/profile_icon.svg" alt="Profile" height="30"></a>';
    echo $profile_block;
} elseif (preg_match('/^\/profile\/(' . $user_id . ')\/?$/', $url, $matches)){
    // Если пользователь залогинен и находится на странице своего профиля, отображаем кнопку выхода
    echo '<a class="nav-link" href="/logout.php"><img src="/static/logout_icon.svg" alt="Profile" height="30"></a>';
} elseif (strpos($url, '/profile') === 0) {
    // На странице профиля ничего не выводим
} else {
    // Если пользователь не залогинен, отображаем кнопки входа и регистрации
    $login_block = '<a class="nav-link nav-reg" href="/login/"><i class="fas fa-sign-in-alt"></i> Login&nbsp;</a>';
    $register_block = '<a class="nav-link nav-reg" href="/register/"><i class="fas fa-user-plus"></i> Register</a>';

    echo $login_block;
    echo $register_block;
}
?>
