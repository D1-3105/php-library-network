<?php
// Подключаемся к базе данных
include "connection.php";
require_once "auth.php";
$libraryId = $USER['library_id'];
?>

<!-- Форма выбора пользователя и книги -->
<form id="filterForm-1">
    <label for="userSelect">Выберите пользователя:</label>
    <select id="userSelect-1" name="userSelect">
        <?php
        // Запрос к базе данных для получения списка актуальных пользователей
        $query_users = "SELECT user_id, name FROM users WHERE user_id IN 
                        (SELECT DISTINCT l.user_id FROM loans l
                        JOIN books b ON l.book_id = b.book_id WHERE b.library_id = ?)";
        
        $stmt_users = $conn->prepare($query_users);
        $stmt_users->bind_param("i", $libraryId);
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();

        // Добавляем опции для пользователей
        while ($row_user = $result_users->fetch_assoc()) {
            echo "<option value='" . $row_user['user_id'] . "'>" . $row_user['name'] . "</option>";
        }
        ?>
    </select>

    <label for="bookSelect">Выберите книгу:</label>
    <select id="bookSelect-1" name="bookSelect">
        <?php
        // Запрос к базе данных для получения списка актуальных книг
        $query_books = "SELECT book_id, title FROM books WHERE library_id = ?";
        $stmt_books = $conn->prepare($query_books);
        $stmt_books->bind_param("i", $libraryId);
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

<table class="table-loans">
    <thead>
        <tr>
            <th>Название книги</th>
            <th>Читатель</th>
            <th>Дата выдачи</th>
            <th>Действие</th>
        </tr>
    </thead>
    
    <tbody id="loans">
        
    </tbody>
</table>

<script>
    // Обработчик отправки формы
    document.getElementById("filterForm-1").addEventListener("submit", function(event) {
        event.preventDefault(); // Предотвращаем отправку формы по умолчанию
        
        // Получаем значения выбранных пользователем и книги
        let user = document.getElementById("userSelect-1").value;
        let book = document.getElementById("bookSelect-1").value;

        // Отправляем GET-запрос на сервер для обновления таблицы
        fetch('/librarian_get_loans.php?userSelect=' + user + '&bookSelect=' + book)
            .then(response => response.text())
            .then(data => {
                // Обновляем тело таблицы
                document.getElementById("loans").innerHTML = data;
                changeHTML();
            })
            .catch(error => {
                alert('Ошибка при отправке запроса:', error);
            });
            
        return false;
    });


    function takeLoan(loanId) {
    // Отправляем GET-запрос на сервер
        fetch('/librarian_disable_loan.php?loan_id=' + loanId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let loanElementId = 'loan-' + loanId;
                    let loanElement = document.getElementById(loanElementId);
                    if (loanElement) {
                        loanElement.remove();
                    }
                    showToast(data.message, true);
                } else {
                    showToast(data.message, false);
                }
            })
            .catch(error => {
                // Выводим сообщение об ошибке при выполнении запроса
                showToast('Произошла ошибка при отправке запроса на сервер.', false);
            });
    }

</script>

