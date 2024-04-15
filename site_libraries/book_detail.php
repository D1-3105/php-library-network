<div class="container mt-5">
    <h1 class="mb-4">Детальная информация о книге</h1>
    <div class="row">
        <?php
        // Подключение к базе данных
        include "connection.php";

        // Получение ID книги
        $book_id = $BOOK_ID;

        // Запрос к базе данных для получения информации о книге
        $query = "SELECT b.*, c.name AS category_name FROM books b
                  LEFT JOIN categories c ON b.category_id = c.category_id
                  WHERE b.book_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Проверка наличия данных
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <div class="col-md-4">
                <img src="/<?php echo $row['book_cover']; ?>" class="img-fluid" alt="Обложка книги">
            </div>
            <div class="col-md-8">
                <h2><?php echo $row['title']; ?></h2>
                <p><strong>Автор:</strong> <?php echo $row['author']; ?></p>
                <p><strong>Библиотека:</strong><a href="/libraries/<?php echo $row['library_id']; ?>"> Библиотека №<?php echo $row['library_id']; ?></a></p>
                <p><strong>Категория:</strong> <?php echo $row['category_name']; ?></p>
            </div>
            <div class="col-md-12 mt-4">
                <h3>Описание</h3>
                <p class="book-description"><?php echo $row['description']; ?></p>
            </div>
            <?php
        } else {
            echo "<div class='col'>Книга не найдена.</div>";
        }

        $stmt->close();
        
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            
            // Проверяем наличие записи в таблице desired_books для данного пользователя и книги
            $query_check_desired = "SELECT db.* FROM desired_books db 
                                    JOIN user_preferences up ON db.preference_id = up.preference_id
                                    WHERE up.user_id = ? AND db.book_id = ?";
            $stmt_check_desired = $conn->prepare($query_check_desired);
            $stmt_check_desired->bind_param("ii", $user_id, $book_id);
            $stmt_check_desired->execute();
            $result_check_desired = $stmt_check_desired->get_result();
        
            if ($result_check_desired->num_rows == 0) {
                // Получаем роль пользователя
                $query_get_role = "SELECT library_role FROM users WHERE user_id = ?";
                $stmt_get_role = $conn->prepare($query_get_role);
                $stmt_get_role->bind_param("i", $user_id);
                $stmt_get_role->execute();
                $result_role = $stmt_get_role->get_result();
                $row_role = $result_role->fetch_assoc();
                $library_role = $row_role['library_role'];
        
                if ($library_role == "READER") {
                    echo '<div class="text-center mt-4">';
                    echo '<button id="btn-want-'. $book_id .'"" class="btn btn-primary btn-want-active" onclick="addToDesiredBooks(' . $book_id . ')">ХОЧУ!</button>';
                    echo '</div>';
                }
            }
        }
        $conn->close();
        ?>
    </div>
</div>

<script src="/js/want.js"></script>