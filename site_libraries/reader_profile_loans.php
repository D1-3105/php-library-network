<?php
// Подключаемся к базе данных
include "connection.php";

// Получаем список пользователей
$query_users = "SELECT user_id, name FROM users";
$result_users = $conn->query($query_users);

// Получаем список книг
$query_books = "SELECT book_id, title FROM books";
$result_books = $conn->query($query_books);

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
?>

<table class="table-loans">
    <thead>
        <tr>
            <th>Название книги</th>
            <th>Дата выдачи</th>
            <th>Дата возврата</th>
        </tr>
    </thead>
    
    <tbody id="loans">
        <?php 
        $query_loans = "SELECT b.title AS book_title, l.loan_date, l.return_date, b.book_id
                        FROM loans l 
                        JOIN books b ON l.book_id = b.book_id 
                        WHERE l.user_id = ?";
        $stmt_loans = $conn->prepare($query_loans);
        $stmt_loans->bind_param("i", $user_id);
        $stmt_loans->execute();
        $result_loans = $stmt_loans->get_result();

        while ($row = $result_loans->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a href='/books/". $row['book_id'] ."'>" . $row['book_title'] . "</a></td>";
            echo "<td>" . $row['loan_date'] . "</td>";
            echo "<td>" . ($row['return_date'] ? $row['return_date'] : "") . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
