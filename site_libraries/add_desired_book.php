<?php
// Подключение к базе данных
include "connection.php";
include "auth.php";
session_start();

// Проверка авторизации пользователя
Auth::requireLogin();

// Проверка наличия идентификатора пользователя в сессии
if (isset($_SESSION['user_id'])) {
    // Получение идентификатора пользователя из сессии
    $user_id = $_SESSION['user_id'];

    // Получение идентификатора книги из GET-запроса
    $book_id = $_GET["book_id"];

    // Проверка типа пользователя
    $query_check_user = "SELECT library_role FROM users WHERE user_id = ? AND library_role = 'READER'";
    $stmt_check_user = $conn->prepare($query_check_user);
    $stmt_check_user->bind_param("i", $user_id);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    // Проверка роли пользователя
    if ($result_check_user->num_rows > 0) {
        // Проверка существования записи в таблице desired_books для данного пользователя и книги
        $query_check_desired = "SELECT * FROM desired_books WHERE preference_id = (SELECT preference_id FROM user_preferences WHERE user_id = ?) AND book_id = ?";
        $stmt_check_desired = $conn->prepare($query_check_desired);
        $stmt_check_desired->bind_param("ii", $user_id, $book_id);
        $stmt_check_desired->execute();
        $result_check_desired = $stmt_check_desired->get_result();

        // Добавление записи в таблицу desired_books, если связь между книгой и предпочтением пользователя еще не существует
        if ($result_check_desired->num_rows == 0) {
            $query_insert_desired = "INSERT INTO desired_books (preference_id, book_id) SELECT user_preferences.preference_id, ? FROM user_preferences WHERE user_preferences.user_id = ?";
            $stmt_insert_desired = $conn->prepare($query_insert_desired);
            $stmt_insert_desired->bind_param("ii", $book_id, $user_id);
            $stmt_insert_desired->execute();

            // Проверка успешного выполнения запроса и формирование соответствующего JSON-ответа
            if ($stmt_insert_desired->affected_rows > 0) {
                echo json_encode(array("success" => true, "message" => "Книга успешно добавлена в желаемые!"));
            } else {
                $error_message = $stmt_insert_desired->error;
                error_log("Ошибка при добавлении книги в желаемые: $error_message");
                echo json_encode(array("success" => false, "message" => "Не удалось добавить книгу в желаемые. Подробности смотрите в журнале ошибок."));
            }
            $stmt_insert_desired->close();
            exit; // Завершаем выполнение скрипта
        } else {
            echo json_encode(array("success" => false, "message" => "Связь между книгой и предпочтением пользователя уже существует."));
            exit; // Завершаем выполнение скрипта
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Доступ запрещен. Пользователь не является читателем."));
        exit; // Завершаем выполнение скрипта
    }
} else {
    echo json_encode(array("success" => false, "message" => "Пользователь не авторизован."));
    exit; // Завершаем выполнение скрипта
}

$stmt_check_user->close();
$conn->close();
?>
