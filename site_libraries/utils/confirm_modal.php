<?php
// Функция confirmModal возвращающая HTML код модального окна
function confirmModal($text, $allow, $deny) {
    echo '<div id="confirmModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>' . $text . '</p>
                <button class="confirm-button" id="confirmDelete">' . $allow . '</button>
                <button class="confirm-button" id="cancelDelete">' . $deny . '</button>
            </div>
        </div>';
}
?>
