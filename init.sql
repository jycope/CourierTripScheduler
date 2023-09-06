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
    unloading_days INT NOT NULL,
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

INSERT INTO Regions (name, travel_duration, unloading_days) VALUES
('Санкт-Петербург', 3, 1),
('Уфа', 4, 1),
('Нижний Новгород', 2, 3),
('Владимир', 1, 2),
('Кострома', 1, 3),
('Екатеринбург', 5, 1),
('Ковров', 2, 1),
('Воронеж', 3, 2),
('Самара', 4, 3),
('Астрахань', 5, 2);
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
