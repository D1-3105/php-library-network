    <link href="/css/books.css" rel="stylesheet">
    
    <div class="container">
        <h1>Информация о книгах</h1>

        <!-- Форма для выбора критерия отбора -->
        <form id="sortForm">
            <label>Выберите сортировку (название книги):</label>
            <select id="sortOrder" name="sort_order">
                <option value="ASC">A-Z</option>
                <option value="DESC">Z-A</option>
            </select>
            <label>Выберите категорию:</label>
            <select id="category" name="category">
                <option value="">Все категории</option>
                <?php
                include "connection.php";
                // Вывод списка категорий из базы данных
                $sql_categories = "SELECT * FROM categories";
                $result_categories = $conn->query($sql_categories);
                if ($result_categories->num_rows > 0) {
                    while($row_category = $result_categories->fetch_assoc()) {
                        echo "<option value='" . $row_category["category_id"] . "'>" . $row_category["name"] . "</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="submit" value="Поиск">
        </form>

        <div id="result"></div>
        
    </div>
    <script src="/js/want.js"></script>
    <script>
        // Обработчик события отправки формы
        document.getElementById('sortForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Отменяем стандартное поведение отправки формы
            var sortOrder = document.getElementById('sortOrder').value;
            var category = document.getElementById('category').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/book_table.php?submit=true&sort_order=' + sortOrder + '&category=' + category, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('result').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        });
    </script>