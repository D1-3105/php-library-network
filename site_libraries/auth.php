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

    public static function getAuthedUser($conn) {
        $uid = self::getUserId();
        if (!$uid) {
            throw new Exception("User is not logged in!");
        }
        $sql = 'SELECT * FROM users WHERE users.user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $USER = $result->fetch_assoc();
            return $USER;
        } else {
            throw new Exception("User not found!");
        }
    }
}

?>