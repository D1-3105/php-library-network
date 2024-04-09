$(document).ready(function(){
    // Обработчик щелчка на адрес электронной почты
    $(".email-field").click(function(){
        var email = $(this).text();
        $("#to").val(email); // Заполняем поле "Получатель" значением email
        $("#emailModal").show();
    });

    // Закрытие модального окна по щелчку на крестик
    $(".close").click(function(){
        $("#emailModal").hide();
    });

    // Обработка отправки сообщения
    $("#emailForm").submit(function(event){
        event.preventDefault();
        var formData = $(this).serialize(); 
        console.log(formData);
        $.ajax({
            url: "/send_email_to.php", 
            type: "POST",
            data: formData, 
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Произошла ошибка при отправке запроса на сервер.");
            }
        });
        $("#emailModal").hide();
    });
});