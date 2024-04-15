<?php
include "connection.php";
require_once "auth.php";
session_start();
// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user = null;
// Обработка запроса от пользователя
if (isset($_GET['submit'])) {
    $sort_order = $_GET['sort_order'];
    $category_id = $_GET['category'];

    // Подготовка SQL-запроса с использованием подстановки параметров
    $sql = "SELECT b.book_id, b.title, b.author, b.book_cover, c.name AS category_name
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.category_id
            WHERE (? = '' OR b.category_id = ?)
            ORDER BY b.title $sort_order";

    $stmt = $conn->prepare($sql);

    // Проверка на ошибку при подготовке запроса
    if(!$stmt) {
        die("Error in preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ii", $category_id, $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Вывод результатов запроса в виде таблицы
        echo "<table>";
        $uid = Auth::getUserId();

        $tableContent = "<th>Название</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Изображение</th>";
        if ($uid && ($user = Auth::getAuthedUser($conn))['library_role'] === 'READER') {
            $tableContent .= "<th>Действие</th>";
        }

        echo "<tr>" . $tableContent . "</tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a class='book-ref' href='/books/" . $row['book_id'] . "'>" . $row["title"] . "</a></td>";
            echo "<td>" . $row["author"] . "</td>";
            echo "<td>" . ($row["category_name"] ? $row["category_name"] : "Нет категории") . "</td>"; // Если значение равно NULL, скрываем столбец
            echo "<td><img height='128' src='/" . $row["book_cover"] . "'></td>";
            if ($uid && $user['library_role'] === 'READER') {
                $existing_desired_book_sql = "SELECT NOT EXISTS(
                    SELECT 1 FROM desired_books db
                    JOIN user_preferences up ON up.preference_id = db.preference_id
                    JOIN users u ON u.user_id = up.user_id
                    WHERE up.preference_id = (SELECT preference_id FROM user_preferences WHERE user_id = ?) AND book_id = ?
                )";
                $stmt_check = $conn->prepare($existing_desired_book_sql);

                if(!$stmt_check) {
                    die("Error in preparing statement: " . $conn->error);
                }

                $stmt_check->bind_param("ii", $uid,  $row["book_id"]);
                $stmt_check->execute();
                $q = $stmt_check->get_result();

                // Проверяем, существует ли объект
                if (!$q->fetch_row()[0]) {
                    echo '<td><button class="btn btn-primary btn-want" disabled>ХОЧУ!</button></td>';
                } else {
                    echo '<td><button id="btn-want-'. $row["book_id"] .'" class="btn btn-primary btn-want-active" onclick="addToDesiredBooks(' . $row["book_id"] . ')">ХОЧУ!</button></td>';
                }
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Нет результатов";
    }
}

// Закрытие соединения с базой данных
$conn->close();
?>
