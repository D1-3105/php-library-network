<?php


class Router {
    private $libraryId;

    public function handleRequest($uri) {        
        // Проверяем, является ли URI переходом на конкретную библиотеку
        if (preg_match('/^\/libraries\/(\d+)\/?$/', $uri, $matches)) {
            $this->libraryId = $matches[1];
            $this->renderView('library_detail.php', 200);
        } elseif ($uri === '/libraries/') {
            // Если URI просто обозначает список библиотек
            $this->renderView('libraries_list.php', 200);
        } elseif ($uri === '/') {
            // Если URI соответствует корневому каталогу
            $this->renderView('home.php', 200);
        } else {
            $this->renderView('404.php', 404);
        }
    }
    
    private function renderView($view, $status = 200) {
        http_response_code($status);
        $libraryId = $this->libraryId;
        include $view;
    }
}
?>
