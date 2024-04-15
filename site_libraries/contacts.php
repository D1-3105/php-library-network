<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .header-contacts {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #555;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
    
    <header class="header-contacts">
        <h1>Обратная связь</h1>
    </header>
    
        <section>
            <form id="contactForm" action="/feedback/send_feedback.php" method="POST">
                    <div>
                        <label for="email">Ваш Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div>
                        <label for="message">Сообщение:</label>
                        <textarea id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit">Отправить</button>
            </form>
            <div id="responseMessage"></div>
            </form>
        </section>
        <script>
        const contactForm = document.getElementById('contactForm');
        const responseMessage = document.getElementById('responseMessage');

        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(contactForm);

            fetch(contactForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Обработка ответа от сервера
                if (data.success) {
                    responseMessage.textContent = data.message;
                    responseMessage.style.color = 'green';
                } else {
                    responseMessage.textContent = data.message;
                    responseMessage.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                responseMessage.textContent = 'Произошла ошибка при отправке запроса';
                responseMessage.style.color = 'red';
            });
        });
    </script>
