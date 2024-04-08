<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/css/detail_lib.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/showdown@1.9.1/dist/showdown.min.js"></script>
<script src="/js/md.js"></script>
<script src="/js/slider.js"></script>

<div class="slider-wrapper">
    <div class="slider-container">
        <div class="slider">
            <?php
            include 'connection.php';
            include "_gen_lib_img.php";
            
            $sql = "SELECT * FROM photos WHERE library_id = " . $libraryId . "";
            $result = $conn->query($sql);
            $photoCount = 0;
            if ($result->num_rows > 0) {
                $first = true; // Флаг для первого слайда
                while($row = $result->fetch_assoc()) {
                    $photoCount ++;
                    echo genDefaultLibImg($row["photo_path"], [
                        "extraClass" => $first ? "slide active" : "slide"
                    ]);
                    $first = false;
                }
            } else {
                echo "Нет фотографий";
            }
            ?>
        </div>
    </div>
</div>
<?php
        if ($photoCount > 1){
            echo "<i class='fas fa-chevron-left prev' onClick='plusSlides(1);'></i>";
            echo "<i class='fas fa-chevron-right next' onClick='minusSlides(1);'></i>";
        }
?>
<h1>Информация о библиотеке</h1>
<div class="container">
    <?php
    include 'connection.php';
    
    $sql = "SELECT * FROM libraries WHERE library_id = " . $libraryId . ""; 
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<th>Название:</th><td>" . $row["name"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Адрес:</th><td>" . $row["address"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Телефон:</th><td>" . $row["phone"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Email:</th><td class='email-field'>" . $row["email"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Нет информации о библиотеке";
    }
    ?>
</div>

<!-- Модальное окно для отправки сообщения -->
<div id="emailModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Отправить сообщение</h2>
        <form id="emailForm">
            <label for="message">Введите ваше сообщение:</label>
            <textarea id="message" name="message" rows="4" cols="50"></textarea><br>
            <button type="submit">Отправить</button>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/js/modal_email_sent.js"></script>

<div class="container">
    <?php
    
    $sql = "SELECT * FROM information_library_page WHERE library_id = " . $libraryId . " AND active = 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<div id='mdText'>";
        while($row = $result->fetch_assoc()) {
            echo "<h2>" . $row["title"]. "</h2>";
            echo "<p class='library-info'>" . ($row["content"]) . "</p>"; 
        }
        echo "</div>";
    } else {
        echo "Нет информации о библиотеке";
    }
    $conn->close();
    ?>
    <script>
        $(document).ready(function(){
            $(".library-info").each(function() {
                $(this).html(markdown_to_html($(this).html()));
            });
        });
    </script>
</div>