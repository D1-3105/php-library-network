<?php
include "connection.php";
include "auth.php";
session_start();

Auth::requireLogin();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_GET["book_id"];

    $conn->begin_transaction();

    try {
        $stmt_check_user = $conn->prepare("SELECT library_role FROM users WHERE user_id = ? AND library_role = 'READER'");
        $stmt_check_user->bind_param("i", $user_id);
        if (!$stmt_check_user->execute()) {
            throw new Exception("Ошибка запроса проверки роли пользователя: " . $stmt_check_user->error);
        }
        $result_check_user = $stmt_check_user->get_result();
        if ($result_check_user->num_rows == 0) {
            throw new Exception("Доступ запрещен. Пользователь не является читателем.");
        }

        $stmt_check_preferences = $conn->prepare("SELECT preference_id FROM user_preferences WHERE user_id = ?");
        $stmt_check_preferences->bind_param("i", $user_id);
        if (!$stmt_check_preferences->execute()) {
            throw new Exception("Ошибка запроса проверки предпочтений пользователя: " . $stmt_check_preferences->error);
        }
        $result_check_preferences = $stmt_check_preferences->get_result();

        if ($result_check_preferences->num_rows == 0) {
            $stmt_insert_preference = $conn->prepare("INSERT INTO user_preferences (user_id, interest) VALUES (?, 'Общее')");
            $stmt_insert_preference->bind_param("i", $user_id);
            if (!$stmt_insert_preference->execute()) {
                throw new Exception("Не удалось создать предпочтения пользователя: " . $stmt_insert_preference->error);
            }
            $preference_id = $stmt_insert_preference->insert_id;
        } else {
            $preference_id = $result_check_preferences->fetch_assoc()['preference_id'];
        }

        $stmt_insert_desired = $conn->prepare("INSERT INTO desired_books (preference_id, book_id) VALUES (?, ?)");
        $stmt_insert_desired->bind_param("ii", $preference_id, $book_id);
        if (!$stmt_insert_desired->execute()) {
            throw new Exception("Не удалось добавить книгу в желаемые: " . $stmt_insert_desired->error);
        }

        if ($stmt_insert_desired->affected_rows == 0) {
            throw new Exception("Книга уже находится в списке желаемых.");
        }

        $conn->commit();
        echo json_encode(array("success" => true, "message" => "Книга успешно добавлена в желаемые!"));
    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage());
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
    }

    $conn->close();
} else {
    echo json_encode(array("success" => false, "message" => "Пользователь не авторизован."));
}
?>
