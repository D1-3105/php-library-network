CREATE TABLE IF NOT EXISTS information_library_page (
    information_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    active BOOLEAN NOT NULL,
    library_id INT,
    FOREIGN KEY (library_id) REFERENCES libraries(library_id),
    UNIQUE (library_id)
);
