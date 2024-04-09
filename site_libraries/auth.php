<?php

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function login($user_id) {
        $_SESSION['user_id'] = $user_id;
    }

    public static function logout() {
        unset($_SESSION['user_id']);
        session_destroy();
    }

    public static function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            throw new Exception('Пользователь не авторизован.');
        }
    }
}

?>