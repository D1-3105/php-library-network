document.addEventListener("DOMContentLoaded", function() {
    var emailModal = document.createElement("div");
    emailModal.id = "emailModal";
    emailModal.className = "modal";
    emailModal.style.display = "none";
    emailModal.innerHTML = `
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Отправить сообщение</h2>
            <form id="emailForm">
                <input type="hidden" name="to" id="recipientEmail" value="">
                <label for="message">Введите ваше сообщение:</label>
                <textarea id="message" name="msg" rows="4" cols="50"></textarea><br>
                <button type="button" id="sendEmailBtn">Отправить</button>
            </form>
        </div>
    `;
    document.body.appendChild(emailModal);

    document.querySelector(".close").addEventListener("click", function() {
        emailModal.style.display = "none";
    });

    document.body.addEventListener("click", function(event) {
        if (event.target.classList.contains("email-field")) {
            var email = event.target.textContent.trim();
            document.getElementById("recipientEmail").value = email;
            emailModal.style.display = "block";
        }
    });

    document.getElementById("sendEmailBtn").addEventListener("click", function() {
        var formData = new FormData(document.getElementById("emailForm"));

        fetch("/send_email_to.php", {
            method: "POST",
            body: formData
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error("Произошла ошибка при отправке запроса на сервер.");
            }
            return response.json();
        })
        .then(function(data) {
            alert(data.message);
        })
        .catch(function(error) {
            alert(error.message);
        });

        emailModal.style.display = "none";
    });
});
