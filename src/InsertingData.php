<?php

namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

class InsertingData {
    public static function insertNewTrip()
    {
        $dbconnect = MySQLConnection::connect();
        $courierName = $_POST["courier_name"];
        $region = $_POST["region"];
        $departureDate = $_POST["departure_date"];
        $arrivalDate = (new \DateTime($departureDate))->modify('+5 days')->format("Y-m-d");
        
        $resultCourier = $dbconnect->query("SELECT id FROM Couriers WHERE name = '$courierName'");

        if ($resultCourier->num_rows > 0) {
            $rowCourier = $resultCourier->fetch_assoc();
            $courierID = $rowCourier["id"];
        } else {
            // echo "Курьер с именем '$courierName' не найден.";
            // exit;
            FormRender::render('index.twig', ['errors' => "Курьер с именем '$courierName' не найден."]);
        }

        $resultRegion = $dbconnect->query("SELECT id FROM Regions WHERE name = '$region'");

        if ($resultRegion->num_rows > 0) {
            $rowRegion = $resultRegion->fetch_assoc();
            $regionID = $rowRegion["id"];
        } else {
            // echo "Регион с именем '$region' не найден.";
            // exit;
            FormRender::render('index.twig', ['errors' => "Регион с именем '$region' не найден."]);
        }

        // Вставляем данные в таблицу TripSchedules
        $sqlInsert = "INSERT INTO TripSchedules (courier_id, region_id, departure_date, arrival_date)
        VALUES ($courierID, $regionID, '$departureDate', '$arrivalDate')";

        $dbconnect->query($sqlInsert);
    }
}