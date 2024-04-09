-- Создание таблицы user_preferences для увлечений пользователей
CREATE TABLE IF NOT EXISTS user_preferences (
    preference_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    interest VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Обновление таблицы users для добавления столбца library_role и library_id
ALTER TABLE users
ADD COLUMN library_role ENUM('LIBRARIAN', 'READER') DEFAULT 'READER',
ADD COLUMN library_id INT DEFAULT NULL,
ADD FOREIGN KEY (library_id) REFERENCES libraries(library_id);
