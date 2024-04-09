<h1>Активные запросы книг:</h1>
<script>
function changeHTML() {
}
</script>
<?php
include "librarian_desired_books.php";
?>
<h1>Выдачи:</h1>
<?php 
include "profile_librarian_loans_table.php";
?>
<h2 style="margin-top: 20px;">
    <a href="/libraries/<?php echo $USER['library_id'] ?>" class="link">
        Перейти к библиотеке
    </a>
</h2>

<script src="/js/modal_email_sent.js"></script>


