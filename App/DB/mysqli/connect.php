<?php
require_once '../app_config.php';

function GetConnectToDB()
{
    $conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    if($conn->connect_error){
        die("Ошибка: " . $conn->connect_error);
    }
    $conn->set_charset('utf8');
    return $conn;
}
?>
