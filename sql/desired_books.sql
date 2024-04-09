
-- Создание таблицы desired_books для связи многие ко многим между user_preferences и books
CREATE TABLE IF NOT EXISTS desired_books (
    preference_id INT,
    book_id INT,
    PRIMARY KEY (preference_id, book_id),
    FOREIGN KEY (preference_id) REFERENCES user_preferences(preference_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id)
);