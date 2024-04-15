// Функция для отправки AJAX-запроса на сервер и отображения тоста
function addToDesiredBooks(bookId) {
    console.log(bookId);
    // Создаем объект XMLHttpRequest
    let xhr = new XMLHttpRequest();

    // Настраиваем AJAX-запрос
    xhr.open("POST", `/add_desired_book.php?book_id=${bookId}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Обработка ответа от сервера
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Успешный ответ от сервера
            let response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Если добавление в корзину прошло успешно, скрываем кнопку "ХОЧУ!"
                let btnWant = document.getElementById(`btn-want-${bookId}`);
                btnWant.style.display = "none";

                // Выводим уведомление о успешном добавлении в корзину
                showToast(response.message, true);
            } else {
                // Если сервер вернул ошибку, выводим уведомление
                showToast("Ошибка: " + response.message, false);
            }
        } else {
            // Если произошла ошибка при обращении к серверу, выводим уведомление
            showToast("Произошла ошибка при отправке запроса на сервер.", false);
        }
    };

    // Отправляем AJAX-запрос
    xhr.send();
    console.log(xhr);
}
