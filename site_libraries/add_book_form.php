<!-- Форма для добавления новой книги -->
<form id="addBookForm" enctype="multipart/form-data">
            <h2>Добавить новую книгу:</h2>
            <label>Название:</label>
            <input type="text" id="newTitle" name="title" required>
            <label>Описание:</label>
            <input type="text" id="newDescription" name="description" required>
            <label>Автор:</label>
            <input type="text" id="newAuthor" name="author">
            <label>Изображение:</label>
            <input type="file" id="newCover" name="cover" required>
            <label>Категория:</label>
            <select id="newCategory" name="category">
                <?php
                // Вывод списка категорий из базы данных
                $result_categories = $conn->query($sql_categories);
                if ($result_categories->num_rows > 0) {
                    while($row_category = $result_categories->fetch_assoc()) {
                        echo "<option value='" . $row_category["category_id"] . "'>" . $row_category["name"] . "</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="submit" value="Добавить">
</form>