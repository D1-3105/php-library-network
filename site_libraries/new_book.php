<?php
include "connection.php";

// Получаем данные из запроса
$newTitle = $_POST['title'];
$newDescription = $_POST['description'];
$newAuthor = $_POST['author'];
$newCategory = $_POST['category'];

// Обработка загруженного изображения
$target_dir = "media/book_covers/";
$target_file = $target_dir . basename($_FILES["cover"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Проверка, является ли файл изображением
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["cover"]["tmp_name"]);
    if($check !== false) {
        echo "Файл является изображением - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "Файл не является изображением.";
        $uploadOk = 0;
    }
}

// Проверка размера файла
if ($_FILES["cover"]["size"] > 500000) {
    echo "Извините, ваш файл слишком большой.";
    $uploadOk = 0;
}

// Разрешаем только определенные форматы файлов
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Извините, только JPG, JPEG, PNG и GIF файлы разрешены.";
    $uploadOk = 0;
}

// Проверяем наличие ошибок при загрузке файла
if ($uploadOk == 0) {
    echo "Извините, ваш файл не был загружен.";
// Если все в порядке, попытаемся загрузить файл
} else {
    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
        echo "Файл ". basename( $_FILES["cover"]["name"]). " успешно загружен.";
    } else {
        echo "Извините, произошла ошибка при загрузке вашего файла.";
    }
}

$stmt = $conn->prepare("INSERT INTO books (title, description, author, book_cover, category_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $newTitle, $newDescription, $newAuthor, $target_file, $newCategory);

if ($stmt->execute()) {
    echo "Новая книга успешно добавлена!";
} else {
    echo "Ошибка при добавлении книги: " . $conn->error;
}

// Закрытие соединения с базой данных
$conn->close();
?>