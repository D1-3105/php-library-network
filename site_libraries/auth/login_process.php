<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../connection.php";

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Подготавливаем запрос для выборки данных пользователя из базы данных
    $sql = "SELECT user_id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверяем, был ли найден пользователь с указанным email
    if ($result->num_rows == 1) {
        // Получаем данные о пользователе
        $user = $result->fetch_assoc();

        // Проверяем правильность введенного пароля
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: /");
            exit;
        } else {
            // Пароль неверный, выводим сообщение об ошибке
            echo "Incorrect email or password.";
        }
    } else {
        // Пользователь с указанным email не найден, выводим сообщение об ошибке
        echo "Incorrect email or password.";
    }

    // Закрываем запрос
    $stmt->close();
}

// Закрываем соединение с базой данных
$conn->close();
?>
