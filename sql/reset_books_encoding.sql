-- Изменение кодировки и сравнения для таблицы categories
ALTER TABLE categories
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Изменение кодировки и сравнения для таблицы books
ALTER TABLE books
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;