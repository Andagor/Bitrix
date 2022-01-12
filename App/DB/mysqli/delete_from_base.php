<?php

require_once 'connect.php';

// Удаление данных из MySQL
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
        }
        else
        {
            $error = "Error: ".$conn->error;
            $conn->close();
            return $error;
        }
    }
    else
    {
        echo "Error: ".$conn->error;
    }
}
?>
