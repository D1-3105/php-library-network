<?php
// Подключение к базе данных
include "connection.php";
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "message" => "Пользователь не авторизован."));
    exit;
}

// Получение идентификатора пользователя
$user_id = $_SESSION['user_id'];

// Получение идентификатора книги для удаления
$book_id = $_GET["book_id"];

// Удаление книги из списка желаемых
$query_delete = "DELETE FROM desired_books WHERE preference_id = (SELECT preference_id FROM user_preferences WHERE user_id = ?) AND book_id = ?";
$stmt_delete = $conn->prepare($query_delete);
$stmt_delete->bind_param("ii", $user_id, $book_id);
$stmt_delete->execute();

// Проверка успешного выполнения запроса и формирование соответствующего JSON-ответа
if ($stmt_delete->affected_rows > 0) {
    echo json_encode(array("success" => true, "message" => "Книга успешно удалена из списка желаемых!"));
} else {
    echo json_encode(array("success" => false, "message" => "Не удалось удалить книгу из списка желаемых."));
}

$stmt_delete->close();
$conn->close();
?>
