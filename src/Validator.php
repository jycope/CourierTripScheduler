<?php

namespace App;

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

    public function validateHasUnloadingDays(string $region, string $departureDate, string $arrivalDate)
    {
        $dbconnect = MySQLConnection::connect(); 
        $sqlArrivalBusy = "
            SELECT MAX(ts.arrival_date)
            FROM TripSchedules ts
            JOIN Regions r ON ts.region_id = r.id
            WHERE r.name = '$region'
            ORDER BY ts.arrival_date DESC;
        ";
        $sqlUnloadingDate = $dbconnect
            ->query("SELECT unloading_days FROM Regions WHERE name = '$region';")
            ->fetch_row()[0];

        $unloadingEndDate = (new \DateTime($arrivalDate))
            ->modify("+$sqlUnloadingDate days")
            ->format("Y-m-d");
        $arrivalDateBusy = (new \DateTime($dbconnect->query($sqlArrivalBusy)->fetch_row()[0]))
            ->format("Y-m-d");

        var_dump("$arrivalDateBusy $departureDate $unloadingEndDate");

        if ($departureDate > $arrivalDateBusy && $departureDate < $unloadingEndDate) {
            $this->errors[] = 'В регионе происходит разгрузка';
        }
    }
}