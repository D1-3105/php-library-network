<link href="/css/profile.css" rel="stylesheet"/>
<?php
include "connection.php";
require_once "auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$USER = Auth::getAuthedUser($conn);

if (!$USER) {
    echo "<p>Нет доступа! <a href='/'>На главную.</a></p>";
}

if ($USER["library_role"] === "READER"){
    include "profile_reader.php";
} elseif ($USER["library_role"] === "LIBRARIAN") {
    include "profile_librarian.php";
}
?>