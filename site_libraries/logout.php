<?php
include "auth.php";
session_start();
Auth::logout();

header("Location: /login/");
exit;
?>
