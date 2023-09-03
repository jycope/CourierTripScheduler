CREATE DATABASE IF NOT EXISTS dbnew;

use dbnew;                                      

CREATE TABLE Couriers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    travel_duration INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO Couriers (name) VALUES
('Курьер 1'),
('Курьер 2'),
('Курьер 3'),
('Курьер 4'),
('Курьер 5'),
('Курьер 6'),
('Курьер 7'),
('Курьер 8'),
('Курьер 9'),
('Курьер 10');

INSERT INTO Regions (name, travel_duration) VALUES
('Санкт-Петербург', 3),
('Уфа', 4),
('Нижний Новгород', 2),
('Владимир', 1),
('Кострома', 1),
('Екатеринбург', 5),
('Ковров', 2),
('Воронеж', 3),
('Самара', 4),
('Астрахань', 5);
CREATE TABLE TripSchedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    courier_id INT NOT NULL,
    region_id INT NOT NULL,
    departure_date DATE NOT NULL,
    arrival_date DATE NOT NULL,
    FOREIGN KEY (courier_id) REFERENCES Couriers(id),
    FOREIGN KEY (region_id) REFERENCES Regions(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
