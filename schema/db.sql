DROP DATABASE IF EXISTS buy_match_app;
CREATE DATABASE buy_match_app;
USE buy_match_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','organizer','admin') DEFAULT 'user',
    active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT NOT NULL,
    team1_name VARCHAR(100) NOT NULL,
    team1_logo VARCHAR(255),
    team2_name VARCHAR(100) NOT NULL,
    team2_logo VARCHAR(255),
    date_time DATETIME NOT NULL,
    duration INT DEFAULT 90, -- minutes
    location VARCHAR(150) NOT NULL,
    max_seats INT DEFAULT 2000,
    status ENUM('pending','approved','refused') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(id)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (match_id) REFERENCES matches(id)
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    match_id INT NOT NULL,
    category_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    qr_code VARCHAR(255), -- identifiant ou QR code
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    match_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (match_id) REFERENCES matches(id)
);

-----------------------------
INSERT INTO users (name, email, password, role) VALUES
('Alice User', 'alice@example.com', '$2y$10$Z4c1CklwnZrqHup074zv0ueswxiYCipKdKEuUbWj2HRHzkyIbPuVC', 'user'), --password=password
('Bob User', 'bob@example.com', '$2y$10$Z4c1CklwnZrqHup074zv0ueswxiYCipKdKEuUbWj2HRHzkyIbPuVC', 'user'),
('John Organizer', 'organizer@example.com', '$2y$10$Z4c1CklwnZrqHup074zv0ueswxiYCipKdKEuUbWj2HRHzkyIbPuVC', 'organizer'),
('Admin Root', 'admin@example.com', '$2y$10$Z4c1CklwnZrqHup074zv0ueswxiYCipKdKEuUbWj2HRHzkyIbPuVC', 'admin');


INSERT INTO matches (
    organizer_id, team1_name, team1_logo,
    team2_name, team2_logo, date_time,
    location, max_seats, status
) VALUES
(3, 'FC Barcelona', 'barca.png', 'Real Madrid', 'real.png',
 '2026-02-10 20:00:00', 'Camp Nou', 90000, 'approved'),

(3, 'Manchester City', 'city.png', 'Liverpool', 'liverpool.png',
 '2026-03-05 21:00:00', 'Etihad Stadium', 55000, 'approved');


INSERT INTO categories (match_id, name, price) VALUES
(1, 'VIP', 250.00),
(1, 'Premium', 150.00),
(1, 'Standard', 80.00),

(2, 'VIP', 220.00),
(2, 'Standard', 90.00);


INSERT INTO tickets (
    user_id, match_id, category_id, seat_number, qr_code
) VALUES
(1, 1, 1, 'A1', 'QR001ABC'),
(1, 1, 2, 'B15', 'QR002ABC'),
(2, 2, 5, 'C10', 'QR003ABC');


INSERT INTO comments (user_id, match_id, rating, comment) VALUES
(1, 1, 5, 'Amazing match, great atmosphere!'),
(2, 1, 4, 'Very good match, seats were comfortable'),
(1, 2, 3, 'Good game but parking was difficult');
