<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Путь к директории, где находится .env файл
$dotenv->load();
$requestUri = $_SERVER['REQUEST_URI'];

use App\FormRender;
use App\MySQLConnection;
use App\Validator;

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
        
            $validator = new Validator();
        
            $validator->validateEmptyField($region);
            
            $validator->validateEmptyField($departureDate);
            $validator->validateDate($departureDate);
        
            $validator->validateEmptyField($courierName);
            $validator->validateFIO($courierName);
            
            FormRender::render('index.twig');
        } elseif ($formType === "filter") {
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];
            
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

            $result = MySQLConnection::connect()->query($sql);

            FormRender::render('filter-form.twig');
        }
    }
}