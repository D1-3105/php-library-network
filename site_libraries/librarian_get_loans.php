<?php
include "connection.php";
session_start();
$user_id = $_SESSION["user_id"];
if($user_id) {
    $sql = 'SELECT * FROM users WHERE users.user_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    $stmt->execute();

    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $USER = $result->fetch_assoc();
    }
}
// Получение параметров фильтрации из формы
$userSelect = $_GET['userSelect'];
$bookSelect = $_GET['bookSelect'];

// Подготовка запроса к базе данных с учетом фильтрации
$query = "SELECT u.email AS reader_email, b.title AS book_title, l.loan_date, l.loan_id
          FROM loans l
          JOIN users u ON l.user_id = u.user_id
          JOIN books b ON l.book_id = b.book_id
          WHERE b.library_id = ? AND l.return_date is NULL";

// Дополнительные условия для фильтрации по выбранным пользователям и книгам
$params = array($USER['library_id']);
if (!empty($userSelect)) {
    $query .= " AND u.user_id = ?";
    $params[] = $userSelect;
}
if (!empty($bookSelect)) {
    $query .= " AND b.book_id = ?";
    $params[] = $bookSelect;
}
// Подготовка и выполнение запроса
$loans_stmt = $conn->prepare($query);
$loans_stmt->bind_param(str_repeat("i", count($params)), ...$params);
$loans_stmt->execute();
$result = $loans_stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr id='loan-".$row['loan_id'] . "'>";
        echo "<td><a class='email-field'>" . $row['reader_email'] . "</a></td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['loan_date'] . "</td>";
        echo "<td><button class='delete-btn' onclick='takeLoan(". $row['loan_id'] .")'>ЗАБРАТЬ</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Список выданных книг пуст</td></tr>";
}
$loans_stmt->close();
?>