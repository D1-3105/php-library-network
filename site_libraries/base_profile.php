<?php
include "connection.php";


$USER = null;

$user_id = $_SESSION["user_id"];
if($user_id) {
    $sql = 'SELECT * FROM users WHERE users.user_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    $stmt->execute();

    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $USER = $result->fetch_assoc();
        if ($USER["library_role"] === "READER"){
            include "profile_reader.php";
        } elseif ($USER["library_role"] === "LIBRARIAN") {
            include "profile_librarian.php";
        }
    } else {
        echo "<p>Нет доступа! <a href='/'>На главную.</a></p>";
    }
    $stmt->close();
}


