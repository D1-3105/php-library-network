<?php
include "_smtp.php";

function send_ehlo($smtp_connection) {
    fputs($smtp_connection, "EHLO\r\n");
    // Проверяем успешность отправки команды EHLO
    $response = fgets($smtp_connection);
    if (strpos($response, '220') === false) {
        $err_msg = "Failed to send EHLO command: $response";
        throw new Exception("Failed to connect to SMTP server: $errstr ($errno)");
    }
}

function send_email($to, $subject, $message, $from) {
    global $smtp_username, $smtp_password, $smtp_connection; // Объявляем глобальные переменные

    // Формируем заголовки письма
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . $from . "\r\n";

    // Проверяем успешность соединения
    if (!$smtp_connection) {
        echo "Failed to connect to SMTP server";
        return false;
    }

    send_ehlo($smtp_connection);

    // Авторизируемся на SMTP сервере
    fputs($smtp_connection, "AUTH LOGIN\r\n");
    fputs($smtp_connection, base64_encode($smtp_username) . "\r\n");
    fputs($smtp_connection, base64_encode($smtp_password) . "\r\n");

    // Отправляем команду MAIL FROM
    fputs($smtp_connection, "MAIL FROM: <$from>\r\n");

    // Отправляем команду RCPT TO
    fputs($smtp_connection, "RCPT TO: <$to>\r\n");

    // Отправляем команду DATA
    fputs($smtp_connection, "DATA\r\n");

    // Отправляем заголовки письма и сообщение
    fputs($smtp_connection, "Subject: $subject\r\n");
    fputs($smtp_connection, "$headers\r\n");
    fputs($smtp_connection, "$message\r\n");

    // Отправляем команду завершения
    fputs($smtp_connection, ".\r\n");

    // Получаем ответ от SMTP сервера
    $response = fgets($smtp_connection);

    // Закрываем соединение с SMTP сервером
    fputs($smtp_connection, "QUIT\r\n");
    fclose($smtp_connection);

    // Проверяем успешность отправки
    if (strpos($response, '250') === false) {
        throw new Exception($response);
    }

    return true;
}

?>
