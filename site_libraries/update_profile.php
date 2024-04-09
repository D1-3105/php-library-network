<?php
include "connection.php";
session_start();
$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name']; // Добавляем получение значения имени из формы
    $email = $_POST['email'];
    $interest = $_POST['interest']; // Добавляем получение значения интереса из формы

    // Обновляем информацию о пользователе
    $query_update_user = "UPDATE users SET name = ?, email = ? WHERE user_id = ?";
    $stmt_update_user = $conn->prepare($query_update_user);
    $stmt_update_user->bind_param("ssi", $name, $email, $user_id);
    $stmt_update_user->execute();
    $stmt_update_user->close();

    // Проверяем, существует ли уже запись для данного пользователя в таблице user_preferences
    $query_check = "SELECT * FROM user_preferences WHERE user_id = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Если запись существует, обновляем её
        $query_update = "UPDATE user_preferences SET interest = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("si", $interest, $user_id);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Если запись не существует, создаем новую запись
        $query_insert = "INSERT INTO user_preferences (user_id, interest) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("is", $user_id, $interest);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
}
header("Location: /profile/" . $user_id);
?>
