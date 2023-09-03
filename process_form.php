<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Путь к директории, где находится .env файл
$dotenv->load();

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('/');
$twig = new Environment($loader);
$template = $twig->load('index.twig');
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/') {    
    echo $template->render();
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $region = $_POST["region"];
    $departureDate = $_POST["departure_date"];
    $courierName = $_POST["courier_name"];

    $validator = new Validator();

    $validator->validateEmptyField($region);
    
    $validator->validateEmptyField($departureDate);
    $validator->validateDate($departureDate);

    $validator->validateEmptyField($courierName);
    $validator->validateFIO($courierName);
    
    echo $template->render(['errors' => $validator->getErrors()]);
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
