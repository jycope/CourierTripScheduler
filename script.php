<?php
// Подключение к базе данных
$servername = '172.17.0.2';
$username = 'dmitry';
$password = 'laker2288';
$dbname = 'dbnew';

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Генерация данных для заполнения
$regions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // ID регионов
$couriers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // ID курьеров

$startDate = date("Y-m-d"); // Текущая дата
$endDate = date("Y-m-d", strtotime("+3 months")); // Текущая дата + 3 месяца

// Заполнение таблицы расписания
foreach ($couriers as $courierID) {
    foreach ($regions as $regionID) {
        $departureDate = date("Y-m-d", strtotime("+$regionID days", strtotime($startDate)));
        print_r($departureDate . "\n");
        $arrivalDate = date("Y-m-d", strtotime("+$regionID days", strtotime($departureDate)));
        print_r($arrivalDate . "\n");

        // SQL-запрос для вставки записи
        $sql = "INSERT INTO TripSchedules (courier_id, region_id, departure_date, arrival_date)
                VALUES ($courierID, $regionID, '$departureDate', '$arrivalDate')";

        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully<br>";
        } else {
            echo "Error inserting record: " . $conn->error . "<br>";
        }
    }
}

// Закрытие соединения
$conn->close();

