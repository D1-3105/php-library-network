-- Создание базы данных
CREATE DATABASE IF NOT EXISTS city_libraries_network;

-- Использование созданной базы данных
USE city_libraries_network;

-- Создание таблицы для библиотек
CREATE TABLE IF NOT EXISTS libraries (
    library_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20)
);

-- Создание таблицы для категорий книг
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Создание таблицы для книг
CREATE TABLE IF NOT EXISTS books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(2048) NOT NULL,
    author VARCHAR(100),
    library_id INT,
    FOREIGN KEY (library_id) REFERENCES libraries(library_id),
    book_cover VARCHAR(255) NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
);

-- Создание таблицы для пользователей
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE
);

-- Создание таблицы для выдачи книг
CREATE TABLE IF NOT EXISTS loans (
    loan_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    user_id INT,
    loan_date DATE,
    return_date DATE,
    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
