<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../connection.php";
include "../smtp/send_email.php";

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Хешируем пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Открываем транзакцию
    $conn->begin_transaction();

    // Подготовленный запрос для вставки данных в таблицу пользователей
    $sql = "INSERT INTO users (name, email, password, library_role) VALUES (?, ?, ?, 'READER')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    // Выполняем запрос
    if ($stmt->execute()) {
        // Формируем сообщение
        $subject = 'Welcome to City Libraries Network';
        $message = '<h1>Welcome to City Libraries Network</h1>';
        $message .= '<p>Thank you for registering, ' . $name . '!</p>';

        // Пытаемся отправить письмо
        if (send_email($email, $subject, $message, 'from@example.com')) {
            header('Location: /registration/success/?state=success');
        } else {}

        // Фиксируем изменения в базе данных
        $conn->commit();
        header('Location: /registration/success/?state=success');
    } else {
        // Откатываем транзакцию в случае неудачи
        $conn->rollback();
        echo 'Failed to create user';
        header('Location: /registration/success/?state=failure');
    }

    // Закрываем запрос
    $stmt->close();
}

// Закрываем соединение с базой данных
$conn->close();
?>
