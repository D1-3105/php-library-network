<?php
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Libraries Network</title>
    <link rel="icon" type="image/svg+xml" href="/static/favicon.svg">
    <link href="/css/stylesheet.css" rel="stylesheet">
    <script src="/js/toasts.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .navbar-brand img {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="/static/logo.svg" alt="City Libraries Network" height="30">
                    <span>City Libraries Network</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Добавленные ссылки -->
                        <li class="nav-item">
                            <a class="nav-link" href="/libraries/">Библиотеки</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/books/">Книги</a>
                        </li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <!-- Конец добавленных ссылок -->
                    <?php include 'navbar.php'; ?>
                </div>
            </div>
        </nav>
    </header>




    <main id="content" class="container py-4">
    <?php
        // base.php
        require_once 'router.php';

        $router = new Router();

        $uri = $_SERVER['REQUEST_URI'];

        $view = $router->handleRequest($uri);
    ?>
    </main>
    <div id="toastContainer" style="display: none;"></div>
    <footer class="bg-dark py-3 mt-auto">
        <div class="container text-light text-center">
            <p>&copy; 2024 City Libraries Network. All rights reserved. <a href="/contacts/" class="text-light">Контакты</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
