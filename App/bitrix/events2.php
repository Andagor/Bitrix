<?php

error_reporting(E_ALL);
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/webhooks/CareField/App/Applog.log');

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

$application_token = $_REQUEST['auth']['application_token'];

if ($application_token == BITRIXAPPLICATIONTOKEN)
{
    $activity_id = $_REQUEST['data']['FIELDS']['ID'];
    $event = $_REQUEST['event'];
    file_put_contents('Applog.log', print_r('activity_id '.$activity_id.PHP_EOL, true), FILE_APPEND);
    file_put_contents('Applog.log', print_r('event '.$event.PHP_EOL.PHP_EOL, true), FILE_APPEND);



    
    
    if ($event == "ONCRMACTIVITYADD")
    {
        $activity_data = GetActivityData($activity_id);
        if ($activity_data)
        {
            $addToBase = Add_to_Base($activity_data, TABLE_NAME);
            if ($addToBase == 'Sucsess added')
            {
                file_put_contents('Applog.log', print_r($addToBase, true), FILE_APPEND);
            }
            else
            {
                file_put_contents('Applog.log', print_r($addToBase, true), FILE_APPEND);
            }
        }
        else
        {
            file_put_contents('Applog.log', print_r("Не зашли в activity_data ".$activity_data, true), FILE_APPEND);
        }
    }





    if ($event == "ONCRMACTIVITYUPDATE")
    {
        $activity_data = GetActivityData($activity_id);
        if ($activity_data)
        {
            $updateInBase = Update_in_Base($activity_data, TABLE_NAME);
            if ($updateInBase == 'Sucsess updated')
            {
                file_put_contents('Applog.log', print_r($updateInBase, true), FILE_APPEND);
            }
            else
            {
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
            $deleteFromBase = Delete_from_Base($activity_id, TABLE_NAME);
            if ($deleteFromBase == 'Sucsess deleted')
            {
                file_put_contents('Applog.log', print_r($deleteFromBase, true), FILE_APPEND);
            }
            else
            {
                file_put_contents('Applog.log', print_r($deleteFromBase, true), FILE_APPEND);
            }
}
else
{
    echo 'Error: no undefyned token '.$application_token;
}
?>
