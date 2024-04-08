<?php

$smtp_server = 'mailhog';
$smtp_username = 'd1';
$smtp_password = '123';
$smtp_port = 1025; 

$smtp_connection = fsockopen($smtp_server, $smtp_port, $errno, $errstr, 30);
if (!$smtp_connection) {
    echo "Failed to connect to SMTP server: $errstr ($errno)";
    die("Connection to smtp failed!");
}
?>