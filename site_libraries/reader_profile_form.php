        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="<?php echo $USER['name']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $USER['email']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <?php
                $interest = '';
                $query = "SELECT * FROM user_preferences WHERE user_id = ?";
                $interest_stmt = $conn->prepare($query);
                if (!$interest_stmt) {
                    die("Ошибка подготовки запроса: " . $conn->error);
                }
                $interest_stmt->bind_param("i", $USER['user_id']);
                $interest_stmt->execute();
                $interest_result = $interest_stmt->get_result();

                if ($interest_result->num_rows > 0) {
                    $row = $interest_result->fetch_assoc();
                    $interest = $row['interest'];
                }

                // Закрываем результат и освобождаем ресурсы
                $interest_result->close();

                // Закрываем запрос и соединение с базой данных
                $interest_stmt->close();
            ?>

            <!-- Форма с данными об увлечениях пользователя -->
            <div>
                <label for="interest">О себе:</label>
                <textarea id="interest" name="interest" class="form-control"><?php echo $interest; ?></textarea>
            </div>

        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>