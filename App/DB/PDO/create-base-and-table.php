<?php

// https://b24.crm.in.ua/webhooks/CareField/App2/DB/mysqli/create-base-and-table.php
//                                MYSQL
// https://metanit.com/php/mysql/3.3.php

$database_host = '';
$database_port = '';
$database_name = 'projects';
$username = 'root';
$password = '0b1Rn_7+AtrTkED_UT7]';



// ПОДКЛЮЧЕНИЕ К БАЗЕ
$conn = new mysqli($database_host, $username, $password, $database_name);
if($conn->connect_error)
{
    die("Ошибка: " . $conn->connect_error);
}
echo "Подключение успешно установлено <br>";



// СОЗДАНИЕ БАЗЫ ДАННЫХ
/*$sql = "CREATE DATABASE projects";
if($conn->query($sql))
{
    echo "База данных успешно создана";
}
else
{
    echo "Ошибка: " . $conn->error;
}*/


// СОЗДАНИЕ ТАБЛИЦЫ
/*$sql = "CREATE TABLE Activity (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    activity_id INTEGER NOT NULL,
    owner_id INTEGER NOT NULL,
    owner_type_id CHAR(10) NOT NULL,
    type_id CHAR(30) NOT NULL,
    provider_id CHAR(30) NOT NULL,
    provider_type_id CHAR(30) NOT NULL,
    associated_entity_id INTEGER,
    subject VARCHAR(255),
    description VARCHAR(700),
    created DATETIME NOT NULL,
    last_updated DATETIME NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    deadline DATETIME NOT NULL,
    completed CHAR(10) NOT NULL,
    status VARCHAR(30) NOT NULL,
    author_id VARCHAR(100) NOT NULL,
    responsible_id VARCHAR(100) NOT NULL,
    priority CHAR(10) NULL,
    direction CHAR(10) NULL,
    location VARCHAR(255),
    com_id INTEGER,
    com_type VARCHAR(30),
    com_value VARCHAR(50),
    com_entity_id INTEGER,
    com_entity_type_id INTEGER,
    client_fio VARCHAR(100),
    lead_or_company_title VARCHAR(100),
    region CHAR(20),
    district CHAR(50),
    locality CHAR(50),
    edrpou CHAR(50),
    what_interested CHAR(100),
    problems VARCHAR(100),
    service VARCHAR(100),
    holding_name VARCHAR(50),
    comment VARCHAR(100),
    land_bank DECIMAL(7, 3),
    soil_treatment_technics CHAR(10),
    spraying_technics CHAR(10),
    harvesting_technics CHAR(10),
    drones CHAR(10),
    machinery_quality CHAR(10),
    client_type_technics CHAR(50),
    equipment_rental CHAR(20),
    equipment_supplier CHAR(50),
    equipment_availability CHAR(10),
    client_type VARCHAR(50),
    lpr_fio VARCHAR(100),
    lpr_phone VARCHAR(50),
    last_date_updated DATETIME NOT NULL,
    UNIQUE (activity_id)
);";

if($conn->query($sql))
{
    echo "Таблица Activity успешно создана";
}
else
{
    echo "Ошибка: " . $conn->error;
}*/


// ВЫВЕСТИ ТАБЛИЦЫ
$sql = "SHOW TABLES FROM $database_name";
$result = $conn->query($sql);
if($result)
{
    while ($row = $result->fetch_row())
    {
        // print_r($row); print "<br>\n";
        echo "<pre>"; print_r($row); echo "</pre>";
    }
}
else
{
    echo "Ошибка: " . $conn->error;
}


// СПИСОК ПОЛЕЙ В КОНКРЕТНОЙ ТАБЛИЦЕ MYSQL
/*$sql = "SHOW COLUMNS FROM Activity;";
$result = $conn->query($sql);
if($result)
{
    while ($row = $result->fetch_row())
    {
        // print_r($row); print "<br>\n";
        echo "<pre>"; print_r($row); echo "</pre>";
    }
}
else
{
    echo "Ошибка: " . $conn->error;
}*/


// ИЗМЕНЕНИЕ ТАБЛИЦЫ
// Изменение названия таблицы
/*$sql = "ALTER TABLE Activity2 RENAME Activity;";
// Или
// $sql = "RENAME TABLE Activity2 TO Activity;";

if($conn->query($sql))
{
    echo "Таблица Activity2 успешно переименована на Activity";
}
else
{
    echo "Ошибка: " . $conn->error;
}*/

// Изменение типа столбца
/*$sql = "ALTER TABLE Activity
MODIFY COLUMN completed CHAR(20) NOT NULL;";

if($conn->query($sql))
{
    echo "Столбец таблицы Activity успешно изменён";
}
else
{
    echo "Ошибка: " . $conn->error;
}*/





// УДАЛЕНИЕ ТАБЛИЦЫ
/*$sql = "DROP TABLE Activity";

if($conn->query($sql))
{
    echo "Таблица Activity2 успешно удалена";
}
else
{
    echo "Ошибка: " . $conn->error;
}*/

//***********************************************************************~~~~~~~~~~~*****************
/*$user = "root";
$pass = "";
$db   = "spoon";

// Подключаемся к СУБД MySQL.
mysql_connect("localhost", $user, $pass)
  or die("Could not connect: ".mysql_error());

// Создаем БД $db - это может делать только суперпользователь!
// Если БД уже существует, будет ошибка, но это не страшно.
@mysql_query("CREATE DATABASE $db");

// Выбираем БД $db
mysql_select_db($db)
  or die("Could not select database: ".mysql_error());

// Получаем все данные таблицы.
$result = mysql_query('SELECT * FROM people');

// Запрашиваем идентификатор данных о полях таблицы.
$fields = mysql_num_fields($result);

// Узнаем число записей в таблице.
$rows   = mysql_num_rows($result);

// Получаем имя таблицы
$table = mysql_field_table($result,0);
echo "Таблица '$table' содержит $fields колонок и $rows строк<BR>";
echo "Таблица содержит следующие поля:<BR>";

// Проходим по всем полям и выводим информацию о них.
for ($i=0; $i<$fields; $i++) {
  $type  = mysql_field_type($result, $i);
  $name  = mysql_field_name($result, $i);
  $len   = mysql_field_len($result, $i);
  $flags = mysql_field_flags($result, $i);
  echo "$name $type($len) $flags<BR>\n";
}*/
//***********************************************************************~~~~~~~~~~~*****************

$conn->close();
?>