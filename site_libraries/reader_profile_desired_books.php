<style>
    .table-desired {
        width: 100%;
        border-collapse: collapse;
    }
    .table-desired th, .table-desired td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .table-desired th {
        background-color: #f2f2f2;
    }
    .table-desired img {
        height: 128px;
        width: auto;
    }
    .delete-btn {
        background-color: #f44336;
        color: white;
        padding: 6px 10px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }
</style>

<h2>Редактирование списка желаемых книг</h2>
<table class="table-desired">
    <thead>
        <tr>
            <th>Название книги</th>
            <th>Обложка</th>
            <th>Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include "connection.php";

        // Получение идентификатора пользователя
        $user_id = $_SESSION['user_id'];

        // Запрос к базе данных для получения списка желаемых книг пользователя
        $query = "SELECT b.title, b.book_cover, db.book_id FROM desired_books db
                  JOIN books b ON db.book_id = b.book_id
                  WHERE db.preference_id = (SELECT preference_id FROM user_preferences WHERE user_id = ?)";
        $desired_stmt = $conn->prepare($query);
        $desired_stmt->bind_param("i", $user_id);
        $desired_stmt->execute();
        $result = $desired_stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><a href='/books/" . $row['book_id'] . "'>" . $row['title'] . "</td>";
                echo "<td><img src='/" . $row['book_cover'] . "' height='128' alt='Обложка'></td>";
                echo "<td><button class='delete-btn' onclick='deleteBook(" . $row['book_id'] . ")'>Удалить</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Список желаемых книг пуст</td></tr>";
        }

        $desired_stmt->close();
        ?>
    </tbody>
</table>

<script>
    function deleteBook(bookId) {
        if (confirm("Вы уверены, что хотите удалить эту книгу из списка желаемых?")) {
            // Создаем объект XMLHttpRequest
            let xhr = new XMLHttpRequest();

            // Настраиваем AJAX-запрос
            xhr.open("POST", `/delete_desired_book.php?book_id=${bookId}`, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Успешный ответ от сервера
                    let response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        location.reload();
                    } else {
                        alert("Ошибка: " + response.message);
                    }
                } else {
                    alert("Произошла ошибка при отправке запроса на сервер.");
                }
            };

            // Отправляем AJAX-запрос
            xhr.send();
        }
    }
</script>
