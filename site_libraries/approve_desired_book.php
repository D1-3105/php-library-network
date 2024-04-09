<?php
include "connection.php";
session_start();


$data = json_decode(file_get_contents("php://input"), true);


if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(array("success" => false, "message" => "Пользователь не авторизован"));
    exit();
}


$librarianId = $_SESSION["user_id"];

$bookId = $data["book_id"];
$preferenceId = $data["preference_id"];

$query_check_librarian = "SELECT b.library_id FROM books b 
                          WHERE b.book_id = ? AND b.library_id = (SELECT library_id FROM users WHERE user_id = ?)";
$stmt_check_librarian = $conn->prepare($query_check_librarian);
$stmt_check_librarian->bind_param("ii", $bookId, $librarianId);
$stmt_check_librarian->execute();
$result_check_librarian = $stmt_check_librarian->get_result();

if ($result_check_librarian->num_rows === 0) {
    http_response_code(403);
    echo json_encode(array("success" => false, "message" => "Библиотекарь не может подтвердить эту выдачу"));
    exit();
}


$query_check_loan = "SELECT * FROM desired_books WHERE preference_id = ? AND book_id = ?";
$stmt_check_loan = $conn->prepare($query_check_loan);
$stmt_check_loan->bind_param("ii", $preferenceId, $bookId);
$stmt_check_loan->execute();
$result_check_loan = $stmt_check_loan->get_result();

if ($result_check_loan->num_rows === 0) {
    http_response_code(404);
    echo json_encode(array("success" => false, "message" => "Данный запрос на выдачу не существует"));
    exit();
}
$query_delete = "DELETE FROM desired_books WHERE book_id = ? AND preference_id = ?";
$stmt_delete = $conn->prepare($query_delete);
$stmt_delete->bind_param("ii", $bookId, $preferenceId);
$stmt_delete->execute();


$query_create_loan = "INSERT INTO loans (book_id, user_id, loan_date, return_date) 
                      SELECT ?, user_id, NOW(), NULL FROM user_preferences WHERE preference_id = ?";
$stmt_create_loan = $conn->prepare($query_create_loan);
$stmt_create_loan->bind_param("ii", $bookId, $preferenceId);
$stmt_create_loan->execute();

if ($stmt_create_loan->affected_rows === 1) {
    echo json_encode(array("success" => true, "message" => "Книга успешно выдана"));
} else {
    http_response_code(500);
    echo json_encode(array("success" => false, "message" => "Ошибка при выдаче книги"));
}

$stmt_check_librarian->close();
$stmt_check_loan->close();
$stmt_create_loan->close();
$conn->close();
?>
