<?php

include 'auth.php';

class Router {
    private $libraryId;
    private $bookId;

    public function handleRequest($uri) {        
        try {
            // Проверяем, является ли URI переходом на конкретную библиотеку
            if (preg_match('/^\/libraries\/(\d+)\/?$/', $uri, $matches)) 
            {
                $this->libraryId = $matches[1];
                $this->renderView('library_detail.php', 200);
            } 
            
            elseif ($uri === '/libraries/') 
            {
                // Если URI просто обозначает список библиотек
                $this->renderView('libraries_list.php', 200);
            } 
            
            elseif ($uri === '/') 
            {
                // Если URI соответствует корневому каталогу
                $this->renderView('home.php', 200);
            } 
            
            elseif (preg_match('/^\/profile\/(\d+)\/?$/', $uri, $matches)) 
            {
                Auth::requireLogin();
                $this->renderView('base_profile.php', 200);
            } 

            elseif ($uri === '/books/')
            {
                $this->renderView("books_list.php");
            }

            elseif (preg_match('/^\/books\/(\d+)\/?$/', $uri, $matches))
            {
                $this->bookId = $matches[1];
                $this->renderView('book_detail.php');
            }

            elseif (strpos('/registration/success/', $uri) == 0) 
            {
                $this->renderView('on_reg.php');
            }
            
            else 
            {
                $this->renderView('404.php', 404);
                echo ''.strpos('/registration/success/', $uri);
            }
        } catch (Exception $e) {
            // Обработка исключения: пользователь не авторизован
            if ($e->getMessage() === 'Пользователь не авторизован.') {
                http_response_code(401);
                echo '<script>alert("' . $e->getMessage() . '");</script>';
            } else {
                throw $e; // Повторное возбуждение исключения
            }
        }
    }
    
    private function renderView($view, $status = 200) {
        http_response_code($status);
        $libraryId = $this->libraryId;
        $BOOK_ID = $this->bookId;
        include $view;
    }
}
?>
