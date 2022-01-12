<?php

require_once 'get_data.php';
require_once '../DB/mysqli/add_to_base.php';
require_once '../DB/mysqli/update_in_base.php';
require_once '../DB/mysqli/delete_from_base.php';

$application_token = $_REQUEST['auth']['application_token'];

if ($application_token == BITRIXAPPLICATIONTOKEN)
{
    $activity_id = $_REQUEST['data']['FIELDS_BEFORE']['ID'];
    $event = $_REQUEST['event'];

    if ($event == "ONCRMACTIVITYADD")
    {
        $activity_data = GetActivityData($activity_id);
        if ($activity_data)
        {
            $addToBase = Add_to_Base($activity_data);
        }
    }

    if ($event == "ONCRMACTIVITYUPDATE")
    {
        $activity_data = GetActivityData($activity_id);
        if ($activity_data)
        {
            $updateInBase = Update_in_Base($activity_data, TABLE_NAME);
        }
    }

    if ($event == 'ONCRMACTIVITYDELETE')
    {
        $activity_data = GetActivityData($activity_id);
        if ($activity_data)
        {
            $updateInBase = Delete_from_Base($activity_data);
        }
    }
}
else
{
    echo 'Error: no undefyned token '.$application_token;
}
?>