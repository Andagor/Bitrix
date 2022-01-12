<?php

// https://b24.crm.in.ua/webhooks/CareField/App2/bitrix/events2.php
// file_put_contents('Applog.log', print_r($_REQUEST, true), FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/webhooks/CareField/App2/Applog.log');

require_once 'get_data.php';
if (DATABASE_MODE == 'mysqli')
{
    require_once '../DB/mysqli/add_to_base.php';
    require_once '../DB/mysqli/update_in_base.php';
    require_once '../DB/mysqli/delete_from_base.php';
}
else
{
    require_once '../DB/PDO/add_to_base.php';
    require_once '../DB/PDO/update_in_base.php';
    require_once '../DB/PDO/delete_from_base.php';
}

echo "events2<br>";

$application_token = $_REQUEST['auth']['application_token'];
// $application_token = 'lfjimrao8f6xkwuto03zqb2cudz28wft';
// $event = 'ONCRMACTIVITYADD';
// $activity_id = 23592;

if ($application_token == BITRIXAPPLICATIONTOKEN)
{
    $activity_id = $_REQUEST['data']['FIELDS']['ID'];
    $event = $_REQUEST['event'];
    file_put_contents('Applog.log', print_r('activity_id '.$activity_id.PHP_EOL, true), FILE_APPEND);
    file_put_contents('Applog.log', print_r('event '.$event.PHP_EOL.PHP_EOL, true), FILE_APPEND);





    if ($event == "ONCRMACTIVITYADD")
    {
        file_put_contents('Applog.log', print_r('Зашли в ONCRMACTIVITYADD'.PHP_EOL.PHP_EOL, true), FILE_APPEND);
        $activity_data = GetActivityData($activity_id);
        echo '<pre>'; print_r($activity_data); echo '</pre>';
        file_put_contents('Applog.log', print_r('Информация об деле'.PHP_EOL, true), FILE_APPEND);
        file_put_contents('Applog.log', print_r($activity_data, true), FILE_APPEND);
        if ($activity_data)
        {
            $addToBase = Add_to_Base($activity_data, TABLE_NAME);
            if ($addToBase == 'Sucsess added')
            {
                echo $addToBase."<br>";
                file_put_contents('Applog.log', print_r($addToBase, true), FILE_APPEND);
            }
            else
            {
                echo "Ошбка ".$addToBase;
                file_put_contents('Applog.log', print_r($addToBase, true), FILE_APPEND);
            }
        }
        else
        {
            echo "Не зашли в activity_data ".$activity_data;
            file_put_contents('Applog.log', print_r("Не зашли в activity_data ".$activity_data, true), FILE_APPEND);
        }
    }





    if ($event == "ONCRMACTIVITYUPDATE")
    {
        file_put_contents('Applog.log', print_r('Зашли в ONCRMACTIVITYUPDATE'.PHP_EOL.PHP_EOL, true), FILE_APPEND);
        $activity_data = GetActivityData($activity_id);
        file_put_contents('Applog.log', print_r('Информация об деле'.PHP_EOL, true), FILE_APPEND);
        file_put_contents('Applog.log', print_r($activity_data, true), FILE_APPEND);
        if ($activity_data)
        {
            $updateInBase = Update_in_Base($activity_data, TABLE_NAME);
            if ($updateInBase == 'Sucsess updated')
            {
                echo $updateInBase."<br>";
                file_put_contents('Applog.log', print_r($updateInBase, true), FILE_APPEND);
            }
            else
            {
                echo "Ошбка ".$update5InBase;
                file_put_contents('Applog.log', print_r($update5InBase, true), FILE_APPEND);
            }
        }
        else
        {
            file_put_contents('Applog.log', print_r("Не зашли в activity_data ".$activity_data, true), FILE_APPEND);
        }
    }





    if ($event == 'ONCRMACTIVITYDELETE')
    {
        file_put_contents('Applog.log', print_r('Зашли в ONCRMACTIVITYDELETE'.PHP_EOL, true), FILE_APPEND);
        // $activity_data = GetActivityData($activity_id);
        // file_put_contents('Applog.log', print_r($activity_data, true), FILE_APPEND);
        // if ($activity_data)
        // {
            $deleteFromBase = Delete_from_Base($activity_id, TABLE_NAME);
            if ($deleteFromBase == 'Sucsess deleted')
            {
                echo $deleteFromBase."<br>";
                file_put_contents('Applog.log', print_r($deleteFromBase, true), FILE_APPEND);
            }
            else
            {
                echo "Ошбка ".$deleteFromBase;
                file_put_contents('Applog.log', print_r($deleteFromBase, true), FILE_APPEND);
            }
        // }
        /*else
        {
            file_put_contents('Applog.log', print_r("Не зашли в activity_data ".$activity_data, true), FILE_APPEND);
        }*/
    }
    // file_put_contents('Applog.log', print_r('Прошли ифы'.PHP_EOL, true), FILE_APPEND);
}
else
{
    echo 'Error: no undefyned token '.$application_token;
}
file_put_contents('Applog.log', print_r(PHP_EOL.'*******************'.PHP_EOL.PHP_EOL, true), FILE_APPEND);
?>