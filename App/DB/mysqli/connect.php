<?php
echo "in connect<br>";
require_once '../app_config.php';
echo "connect after require_once<br>";
function GetConnectToDB()
{
    $conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    if($conn->connect_error){
        die("Ошибка: " . $conn->connect_error);
    }
    $conn->set_charset('utf8');
    echo "Подключение успешно установлено <br>";
    echo "В connect<br>".$conn->info;
    return $conn;
}

    // Выборка данных из таблицы
/*    $sql = "SELECT * FROM Activity";

    if($result = $conn->query($sql))
    {
        $rowsCount = $result->num_rows; // количество полученных строк
        echo "<p>Получено объектов: $rowsCount</p>";
        foreach($result as $row)
        {
            echo '<pre>'; print_r($row); echo '</pre>';
        }
        $result->free();
    } else
    {
        echo "Ошибка: " . $conn->error;
    }*/

    // Добавление данных в MySQLi
/*    $sql = "INSERT INTO Activity (activity_id, owner_id, owner_type_id, type_id, provider_id, provider_type_id, associated_entity_id, subject, created, last_updated, start_time, end_time, deadline, completed, status, responsible_id, priority, description, direction, location, author_id, com_id, com_type, com_value, com_entity_id, com_entity_type_id, com_entity_settings_fio, com_entity_settings_lead_or_company_title, region, district, locality, edrpou, what_interested, problems, service, holding_name, comment, land_bank, soil_treatment_technics, spraying_technics, harvesting_technics, drones, machinery_quality, client_type_technics, equipment_rental, equipment_supplier, equipment_availability, client_type, lpr_fio, lpr_phone, active, last_date_updated)
        VALUES (1, 1, 1, 3, 'TASKS', 'TASK', 7, 'Дізнатися потребу клієнта', '2020-06-24T16:23:11', '2020-07-30T16:37:06', '2020-06-24T17:23:11', '2020-07-30T16:37:06', '2020-07-30T16:37:06', 'Y', 2, 1, 2, 'Якщо звертається дистрибютор', 0, '', 1, 3, 'PHONE', '+380938135152', 1, 1, 'Ваильев Вася Васечкин', 'Лід 1', 'Днепр', 'Васильковский', 'Славное', '777777777777', 'Добрива', '', 'Сервис', 'holding_name', 'comment', 777.75, 'soil_treatment_technics', 'spraying_technics', 'harvesting_technics', 'Есть', 'machinery_quality', 'Купує в будь-якому випадку', 'equipment_rental', 'equipment_supplier', 'equipment_availability', 'Агрохолдинг', 'Иванко Иван Иванович', '+380777777777', 1, '2021-12-17T17:23:11')";
    if($conn->query($sql)){
        echo "Данные успешно добавлены";
    } else{
        echo "Ошибка: " . $conn->error;
    }*/

    // Множественное добавление:
/*    $sql = "INSERT INTO Users (name, age) VALUES
            ('Sam', 41),
            ('Bob', 29),
            ('Alice', 32)";
    if($conn->query($sql)){
        echo "Данные успешно добавлены";
    } else{
        echo "Ошибка: " . $conn->error;
    }*/

/*try {
  # SQLite
  $db = new PDO("sqlite:people.db");
  // $db = new SQLite("people.db");
  echo 'Good!';
}
catch(PDOException $e) {
    echo $e->getMessage();
}*/

// $conn->close();
?>