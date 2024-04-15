<?php
// Подключаемся к базе данных
include "connection.php";

$libraryId = $USER['library_id'];
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


<?php
include "utils/confirm_modal.php";
confirmModal("Вы уверены, что хотите выдать эту книгу?", "Подтвердить", "Отмена");
?>

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
function confirmAction(preferenceId, bookId, callback) {
    const modal = document.getElementById('confirmModal');
    const close = document.querySelector('#confirmModal .close');
    const confirmAction = document.getElementById('confirmDelete');
    const cancelAction = document.getElementById('cancelDelete');
    const modalText = document.querySelector('#confirmModal p');
    modal.style.display = 'block';

    close.onclick = function() {
        modal.style.display = 'none';
    };

    cancelAction.onclick = function() {
        modal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    confirmAction.onclick = function() {
        modal.style.display = 'none';
        callback(preferenceId, bookId);
    };
}

function approveBook(preferenceId, bookId) {
    fetch('/approve_desired_book.php', {
        method: 'POST',
        body: JSON.stringify({ book_id: bookId, preference_id: preferenceId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        } else {
            showToast('Ошибка при одобрении книги', false);
        }
    })
    .catch(error => {
        console.error('Ошибка при отправке запроса:', error);
    });
}

function confirmApprove(preferenceId, bookId) {
    confirmAction(preferenceId, bookId, approveBook);
}


</script>
