<?php
include "../connection.php";
include "../smtp/send_email.php";

$to = "admin@php.com";
$subject = "Новое сообщение от " . $_POST['email'];
$message = $_POST["message"];

// Отправляем письмо
if(send_email($to, $subject, $message, $_POST['email'])) {
    echo json_encode(array("success" => true, "message" => "Письмо успешно отправлено!"));
} else {
    echo json_encode(array("success" => false, "message" => "Ошибка при отправке письма!" . var_dump($_POST)));
}

?>
