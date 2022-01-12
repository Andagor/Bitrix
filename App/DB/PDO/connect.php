<?php
echo "in connect<br>";
require_once '../app_config.php';
echo "connect after require_once<br>";

function GetConnectToDB()
{
    echo "in connect => GetConnectToDB<br>";
    try {
    $conn = new PDO(DATABASE_TYPE.":host=".DATABASE_HOST."; port=".DATABASE_PORT."; dbname=".DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        echo "Подключение успешно установлено <br>";
    }
    catch (PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }

    return $conn;
}
?>