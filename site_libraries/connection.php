<?php
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "city_libraries_network";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>