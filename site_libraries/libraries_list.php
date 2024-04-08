<style>
    /* Стили для плитки */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Автоматическое выравнивание элементов в ряду */
        gap: 20px; /* Промежуток между элементами */
    }

    .library-card {
        border: 1px solid #ccc; /* Граница для каждой карточки */
        padding: 20px;
        text-align: center;
        position: relative; /* Позиционирование относительно родительского элемента */
        overflow: hidden; /* Скрытие части изображения, выходящей за границы карточки */
        cursor: pointer; /* Указатель пальца при наведении */
        display: block; /* Делаем ссылку блочным элементом */
        color: inherit; /* Наследуем цвет текста */
        text-decoration: none; /* Убираем подчеркивание */
    }

    .library-card img {
        max-width: 100%; /* Максимальная ширина изображения в карточке */
        position: relative; /* Относительное позиционирование */
        z-index: 1; /* Изображение находится над градиентом */
    }

    .gradient-overlay {
        position: absolute; /* Абсолютное позиционирование */
        top: 0; /* Расположение градиента в верхней части карточки */
        left: 0; /* Расположение градиента в левой части карточки */
        width: 100%; /* Ширина градиента - 100% ширины карточки */
        height: 100%; /* Высота градиента - 100% высоты карточки */
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,1)); /* Градиент от прозрачного до белого */
        z-index: 2; /* Градиент находится над текстом */
        opacity: 0; /* Начальная непрозрачность градиента */
        transition: opacity 0.3s; /* Плавное изменение непрозрачности при ховере */
    }

    .library-details {
        position: relative; /* Позиционирование относительно родительского элемента */
        z-index: 3; /* Текст находится над градиентом */
    }

    /* При ховере на карточку */
    .library-card:hover .gradient-overlay {
        opacity: 1; /* Сделать градиент полностью непрозрачным */
    }
</style>

<div class="grid-container">
    <?php
    // Подключение к базе данных
    include "connection.php";
    include "_gen_lib_img.php";

    // Запрос на получение списка библиотек с изображениями
    $sql = "SELECT DISTINCT libraries.*, IFNULL(photos.photo_path, '') AS photo_path
            FROM libraries 
            LEFT JOIN photos ON libraries.library_id = photos.library_id AND photos.is_avatar = true";
    $result = mysqli_query($conn, $sql);
    // Проверка наличия данных
    if (mysqli_num_rows($result) > 0) {
        // Вывод списка библиотек с изображениями
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<a href='/libraries/" . $row["library_id"] . "/' class='library-card'>";
            echo genDefaultLibImg($row["photo_path"], [
                "height" => 200
            ]);
            echo "<div class='gradient-overlay'></div>";
            echo "<div class='library-details'>";
            echo "<h2>" . $row['name'] . "</h2>";
            echo "<p><strong>Address:</strong> " . $row['address'] . "</p>";
            echo "<p><strong>Phone:</strong> " . $row['phone'] . "</p>";
            echo "</div>"; // Закрытие .library-details
            echo "</a>"; // Закрытие ссылки
        }
    } else {
        echo "<p>No libraries found</p>";
    }

    // Закрытие соединения с базой данных
    mysqli_close($conn);
    ?>
</div>
