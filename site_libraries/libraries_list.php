<link href="/css/libraries.css" rel="stylesheet">
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
            echo "</div>";
            echo "</a>";
        }
    } else {
        echo "<p>No libraries found</p>";
    }

    // Закрытие соединения с базой данных
    mysqli_close($conn);
    ?>
</div>
