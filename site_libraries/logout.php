<?php
// Начинаем сеанс, если он еще не начат
session_start();

// Разрушаем все переменные сессии
$_SESSION = array();

// Если требуется уничтожить сессию, также удалите сессионные cookie
// Обратите внимание: это уничтожит сеанс, а не только данные сеанса!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Наконец, разрушаем сессию
session_destroy();

// Перенаправляем пользователя на страницу входа
header("Location: /login.php");
exit;
?>
