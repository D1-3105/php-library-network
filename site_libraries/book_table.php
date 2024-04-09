<?php
include "connection.php";

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка запроса от пользователя
if (isset($_GET['submit'])) {
    $sort_order = $_GET['sort_order'];
    $category_id = $_GET['category'];

    // Запрос к базе данных с учетом выбранной категории
    $sql = "SELECT b.book_id, b.title, b.description, b.author, b.book_cover, c.name AS category_name
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.category_id
            WHERE ('$category_id' = '' OR b.category_id = '$category_id')
            ORDER BY b.title $sort_order";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Вывод результатов запроса в виде таблицы
        echo "<table>";
        echo "<tr>
                <th>Название</th>
                <th>Автор</th>
                <th>Категория</th>
                <th>Изображение</th>
            </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a class='book-ref' href='/books/" . $row['book_id'] . "'>" . $row["title"] . "</a></td>";
            echo "<td>" . $row["author"] . "</td>";
            echo "<td>" . ($row["category_name"] ? $row["category_name"] : "Нет категории") . "</td>"; // Если значение равно NULL, скрываем столбец
            echo "<td><img height='128' src='/" . $row["book_cover"] . "'></td>";
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
