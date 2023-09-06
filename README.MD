composer install
composer update


-сборка образа и запуск контейнера mysql
docker build -t mysql_db .
docker run mysql_db


php -S localhost:8000 process_form.php

/ внесение данных в форму
/filter-form фильтрация


script.php скрипт заполнения