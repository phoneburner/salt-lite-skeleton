CREATE TABLE users
(
    id         INT AUTO_INCREMENT
        PRIMARY KEY,
    username   VARCHAR(255) NULL,
    date_added TIMESTAMP    NOT NULL
)
    COLLATE = utf8mb3_unicode_ci;

INSERT INTO users (username, date_added) VALUES ('andysnell', NOW());

