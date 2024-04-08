
CREATE TABLE IF NOT EXISTS photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    description TEXT,
    library_id INT,
    is_avatar BOOLEAN,

    FOREIGN KEY (library_id) REFERENCES libraries(library_id),
);
