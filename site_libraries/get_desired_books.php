<?php
// Подключаемся к базе данных
include "connection.php";

// Получаем параметры запроса
$user_id = $_GET['user'] ?? null;
$book_id = $_GET['book'] ?? null;
// Запрос к базе данных для получения обновленных строк таблицы желаемых книг
$query = "SELECT b.title, u.email, up.preference_id, b.book_id
          FROM desired_books db
          JOIN books b ON db.book_id = b.book_id
          JOIN user_preferences up ON db.preference_id = up.preference_id
          JOIN users u ON u.user_id = up.user_id";

// Добавляем условия WHERE, если user_id и book_id не равны null
if ($user_id !== null && $book_id !== '') {
    $query .= " WHERE up.user_id = ? AND b.book_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
} elseif ($user_id !== null) {
    $query .= " WHERE up.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
} elseif ($book_id !== '') {
    $query .= " WHERE b.book_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $book_id);
} else {
    $stmt = $conn->prepare($query);
}
$stmt->execute();
$result = $stmt->get_result();


// Формируем HTML-код обновленных строк таблицы
$html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>";
        $html .= "<td>" . $row['title'] . "</td>";
        $html .= "<td><a class='email-field'>" . $row['email'] . "</a></td>";
        $html .= "<td><button class='success-btn' onclick='confirmApprove(" . $row['preference_id'] . ", " . $row['book_id'] . ")'>Выдать</button></td>";
        $html .= "</tr>";
    }
} else {
    $html .= "<tr><td colspan='3'>Нет желаемых книг для выбранного пользователя</td></tr>";
}

// Возвращаем HTML-код обновленных строк таблицы
echo $html;

// Закрываем соединение с базой данных
$stmt->close();
$conn->close();
?>
