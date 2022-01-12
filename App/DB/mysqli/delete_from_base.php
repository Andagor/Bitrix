<?php

require_once 'connect.php';

// Обновление данных в MySQL
function Delete_from_Base($activity_data, $table_name)
{
    $conn = GetConnectToDB();
    if ($conn)
    {
        echo "good ".$conn->info;
        $sql = "DELETE FROM $table_name
                WHERE activity_id = '{$activity_data['activity_id']}';";
        if ($conn->query($sql))
        {
            $conn->close();
            return "Sucsess deleted";
            // return "Sucsess deleted ".$activity_data['activity_id'];
        }
        else
        {
            $error = "Error with delete: ".$activity_data['activity_id'].' '.$conn->error;
            $conn->close();
            return $error;
        }
    }
    else
    {
        echo "no good ".$conn->error;
    }
}
?>