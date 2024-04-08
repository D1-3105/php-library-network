<h1>Welcome to City Libraries Network</h1>
<?php
include "./connection.php";

$is_logged_in = isset($_SESSION['user_id']);
if ($is_logged_in) {
    // Получаем имя пользователя из базы данных (замените 'users' на вашу таблицу пользователей)
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $username = $user_data['name'];
        echo "<p>Welcome back, $username!</p>";
    } else {
        echo "<p>Welcome back, User!</p>";
    }
} else {
    echo "<p>Welcome to our library network! Please log in to access more features.</p>";
}
$conn->close();
?>

<div class="links">
    <!-- Подключаем ссылку на список библиотек -->
    <a href="/libraries/">Libraries List</a>

    <!-- Подключаем ссылку на список книг -->
    <a href="books.php">Books List</a>

    <!-- Подключаем ссылку на профиль пользователя -->
    <a href="profile.php">User Profile</a>
</div>
