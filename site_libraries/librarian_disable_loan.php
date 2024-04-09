<?php
// Подключение к базе данных
include "connection.php";

// Проверка, является ли пользователь библиотекарем
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
if ($USER["library_role"] !== "LIBRARIAN") {
    // Если пользователь не является библиотекарем, возвращаем сообщение об ошибке
    $response = array("success" => false, "message" => "Доступ запрещен. Только библиотекари могут выполнять эту операцию.");
    echo json_encode($response);
    exit();
}

// Получение данных из GET-запроса
$loanId = $_GET["loan_id"];

// Проверка, является ли книга библиотекаря
$query = "SELECT COUNT(*) AS count FROM loans l
          JOIN books b ON l.book_id = b.book_id
          WHERE l.loan_id = ? AND b.library_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $loanId, $USER["library_id"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $row["count"];

if ($count == 0) {
    // Если книга не принадлежит библиотекарю, возвращаем сообщение об ошибке
    $response = array("success" => false, "message" => "Книга не принадлежит вашей библиотеке.");
    echo json_encode($response);
    exit();
}

// Обновление даты возврата на текущую
$returnDate = date("Y-m-d"); // Получаем текущую дату
$query_update = "UPDATE loans SET return_date = ? WHERE loan_id = ?";
$stmt_update = $conn->prepare($query_update);
$stmt_update->bind_param("si", $returnDate, $loanId);
$stmt_update->execute();

if ($stmt_update->affected_rows > 0) {
    // Если обновление прошло успешно, возвращаем сообщение об успешном завершении операции
    $response = array("success" => true, "message" => "Книга успешно принята обратно.");
    echo json_encode($response);
} else {
    // Если не удалось обновить данные, возвращаем сообщение об ошибке
    $response = array("success" => false, "message" => "Произошла ошибка. Пожалуйста, попробуйте еще раз.");
    echo json_encode($response);
}

// Закрытие подготовленного запроса и соединения
$stmt->close();
$stmt_update->close();
$conn->close();
?>
