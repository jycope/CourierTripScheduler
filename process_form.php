<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Путь к директории, где находится .env файл
$dotenv->load();

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\MySQLConnection;

$loader = new FilesystemLoader('/');
$twig = new Environment($loader);
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/') {
    $formInsert = $twig->load('index.twig');
    echo $formInsert->render();
}

if ($requestUri === '/filter-form') {
    $filterForm = $twig->load('filter-form.twig');
    echo $filterForm->render();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
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
    $filterForm = $twig->load('filter-form.twig');
    echo $filterForm->render(['result' => $result]);
}


class Validator {
    private $errors = [];

    public function validateFIO(string $FIO)
    {
        $pattern = '/^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$/u';

        if (!preg_match($pattern, $FIO)) {
            $this->errors[] = 'ФИО неверное';
        }
    }
    
    public function validateEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Введите правильный адрес электронной почты.';
        }
    }

    public function validateEmptyField(string $fieldName)
    {
        if (empty($fieldName)) {
            $this->errors[] = 'Поле не должно быть пустым.';
        }
    }

    public function validateStrLen(string $fieldName)
    {
        if (strlen($fieldName) > 100) {
            $this->errors[] = 'Поле не должно превышать 100 символов.';
        }
    }

    public function validateDate(string $dateToValidate)
    {
        [$year, $month, $day] = explode('-', $dateToValidate);

        if (!checkdate($month, $day, $year)) {
            $this->errors[] = 'Дата не корректна';            
        }
    }

    public function validateNumField(string $numericField)
    {
        if (!is_numeric($numericField)) {
            $this->errors[] = 'Поле должно быть числовым значением.';
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
