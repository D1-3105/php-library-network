<?php
session_start();
include "connection.php";
include "smtp/send_email.php";

$user_id = $_SESSION["user_id"];

if (empty($user_id)){
    echo json_encode(array("success" => false, "message" => "Неавторизован!"));
    exit();
}

$sql = 'SELECT * FROM users WHERE users.user_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
    
if ($result->num_rows > 0) {
    $USER = $result->fetch_assoc();
} else {
    echo json_encode(array("success" => false, "message" => "Пользователь не найден!"));
    exit();
}

$to = $_POST["to"];
$from = $USER['email'];
$subject = "Новое сообщение от " . $USER["name"];
$msg = $_POST["msg"];

if(send_email($to, $subject, $msg, $from)){
    echo json_encode(array("success" => true, "message" => "Письмо отправлено!"));
} else {
    echo json_encode(array("success" => false, "message" => "Ошибка при отправке письма!"));
}
?>
