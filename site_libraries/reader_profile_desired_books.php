<style>
    
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
<?php
include "utils/confirm_modal.php";
confirmModal("Вы действительно не хотите получить эту книгу?", "Не хочу!", "Хочу!");
?>
<script>
function deleteBook(bookId) {
    const modal = document.getElementById('confirmModal');
    const close = document.querySelector('.close');
    const confirmDelete = document.getElementById('confirmDelete');
    const cancelDelete = document.getElementById('cancelDelete');

    modal.style.display = 'block'; // Показываем модальное окно

    close.onclick = function() {
        modal.style.display = 'none';
    };

    cancelDelete.onclick = function() {
        modal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    confirmDelete.onclick = function() {
        modal.style.display = 'none';
        sendDeleteRequest(bookId);
    };
}

function sendDeleteRequest(bookId) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", `/delete_desired_book.php?book_id=${bookId}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                location.reload();
            } else {
                showToast("Ошибка: " + response.message, true);
            }
        } else {
            showToast("Произошла ошибка при отправке запроса на сервер.", false);
        }
    };
    xhr.send();
}
</script>
