<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\FormRender;
use App\InsertingData;
use App\MySQLConnection;
use App\Validator;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Путь к директории, где находится .env файл
$dotenv->load();
$requestUri = $_SERVER['REQUEST_URI'];
$dbconnect = MySQLConnection::connect();

if ($requestUri === '/') {
    FormRender::render('index.twig');
}

if ($requestUri === '/filter-form') {
    FormRender::render('filter-form.twig');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["form_type"])) {
        $formType = $_POST["form_type"];
        
        if ($formType === "insert") {
            $region = $_POST["region"];
            $departureDate = $_POST["departure_date"];
            $courierName = $_POST["courier_name"];
            $arrivalDate = (new DateTime($departureDate))->modify('+5 days')->format("Y-m-d");
        
            $validator = new Validator();
            $validator->validateHasUnloadingDays($region, $departureDate);
        
            $validator->validateEmptyField($region);
            
            $validator->validateEmptyField($departureDate);
            $validator->validateDate($departureDate);
        
            $validator->validateEmptyField($courierName);
            
            if ($validator->getErrors()) {
                FormRender::render('index.twig', ['errors' => $validator->getErrors()]);
            } else {
                FormRender::render('index.twig');
                InsertingData::insertNewTrip();
            }
        } elseif ($formType === "filter") {
            $validator = new Validator();
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];

            $validator->validateEmptyField($startDate);
            $validator->validateDate($startDate);

            $validator->validateEmptyField($endDate);
            $validator->validateDate($endDate);
            
            $sql = "SELECT 
                c.name AS courier_name,
                r.name AS region_name,
                ts.departure_date,
                ts.arrival_date
                FROM TripSchedules ts
                JOIN Couriers c ON ts.courier_id = c.id
                JOIN Regions r ON ts.region_id = r.id
                WHERE ts.departure_date BETWEEN '$startDate' AND '$endDate';
            ";

            $result = $dbconnect->query($sql);

            FormRender::render('filter-form.twig', ['result' => $result]);
        }
    }
}