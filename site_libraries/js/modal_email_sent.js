$(document).ready(function(){
    // Обработчик щелчка на адрес электронной почты
    $(".email-field").click(function(){
        var email = $(this).text();
        $("#emailModal").show();
    });

    // Закрытие модального окна по щелчку на крестик
    $(".close").click(function(){
        $("#emailModal").hide();
    });

    // Обработка отправки сообщения
    $("#emailForm").submit(function(event){
        event.preventDefault(); // Предотвращаем отправку формы по умолчанию
        var email = $(".email-field").text();
        var message = $("#message").val();
        // Здесь можно добавить логику отправки сообщения через AJAX
        $("#emailModal").hide(); // Закрываем модальное окно после отправки сообщения
        alert("Сообщение отправлено успешно!");
    });
});