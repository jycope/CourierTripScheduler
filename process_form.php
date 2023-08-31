<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $region = $_POST["region"];
    $departureDate = $_POST["departure_date"];
    $courierName = $_POST["courier_name"];
    
    header("Location: index.html");
    exit();
}
?>
