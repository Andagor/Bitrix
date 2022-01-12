<?php
require_once '../app_config.php';

function GetConnectToDB()
{
    try {
        $conn = new PDO(DATABASE_TYPE.":host=".DATABASE_HOST."; port=".DATABASE_PORT."; dbname=".DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    }
    catch (PDOException $e) {
        echo "Ошибка: " . $e->getMessage();
    }

    return $conn;
}
?>
