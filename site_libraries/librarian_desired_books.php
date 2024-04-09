<?php
// Подключаемся к базе данных
include "connection.php";

// Получаем список пользователей
$query_users = "SELECT user_id, name FROM users";
$result_users = $conn->query($query_users);

// Получаем список книг
$query_books = "SELECT book_id, title FROM books";
$result_books = $conn->query($query_books);
?>

<!-- Форма выбора пользователя и книги -->
<form id="filterForm">
    <label for="userSelect">Выберите пользователя:</label>
    <select id="userSelect" name="userSelect">
        <?php
        // Запрос к базе данных для получения списка актуальных пользователей
        $query_users = "SELECT user_id, name FROM users WHERE user_id IN (SELECT DISTINCT user_id FROM desired_books)";
        $stmt_users = $conn->prepare($query_users);
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();

        // Добавляем опции для пользователей
        while ($row_user = $result_users->fetch_assoc()) {
            echo "<option value='" . $row_user['user_id'] . "'>" . $row_user['name'] . "</option>";
        }
        ?>
    </select>

    <label for="bookSelect">Выберите книгу:</label>
    <select id="bookSelect" name="bookSelect">
        <?php
        // Запрос к базе данных для получения списка актуальных книг
        $query_books = "SELECT book_id, title FROM books WHERE library_id = ?";
        $stmt_books = $conn->prepare($query_books);
        $stmt_books->bind_param("i", $libraryId); // Предполагается, что $libraryId содержит идентификатор библиотеки библиотекаря
        $stmt_books->execute();
        $result_books = $stmt_books->get_result();

        // Добавляем опции для книг
        while ($row_book = $result_books->fetch_assoc()) {
            echo "<option value='" . $row_book['book_id'] . "'>" . $row_book['title'] . "</option>";
        }
        ?>
    </select>

    <button class="filter-button" type="submit">Применить</button>
</form>

<table class="table-desired">
    <thead>
        <tr>
            <th>Название книги</th>
            <th>Читатель</th>
            <th>Действие</th>
        </tr>
    </thead>
    
    <tbody id="desiredBooksBody">
        
    </tbody>
</table>

<script>
// Обработчик отправки формы
document.getElementById("filterForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Предотвращаем отправку формы по умолчанию

    // Получаем значения выбранных пользователем и книги
    var user = document.getElementById("userSelect").value;
    var book = document.getElementById("bookSelect").value;

    // Отправляем GET-запрос на сервер для обновления таблицы
    fetch('/get_desired_books.php?user=' + user + '&book=' + book)
        .then(response => response.text())
        .then(data => {
            // Обновляем тело таблицы
            document.getElementById("desiredBooksBody").innerHTML = data;
            changeHTML();
        })
        .catch(error => {
            console.error('Ошибка при отправке запроса:', error);
        });
});
    function confirmApprove(preferenceId, bookId) {
        if (confirm("Вы уверены, что хотите выдать эту книгу?")) {
            // Отправка POST-запроса на сервер для одобрения книги
            fetch('/approve_desired_book.php', {
                method: 'POST',
                body: JSON.stringify({ book_id: bookId, preference_id: preferenceId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Перезагрузка страницы после успешного одобрения книги
                    location.reload();
                } else {
                    // Уведомление пользователя об ошибке
                    alert('Ошибка при одобрении книги');
                }
            })
            .catch(error => {
                console.error('Ошибка при отправке запроса:', error);
            });
        }
    }
</script>
