<?php

require_once 'connect.php';

// Удаление данных из MySQL
function Delete_from_Base($activity_id, $table_name)
{
    $conn = GetConnectToDB();
    if ($conn)
    {
        echo "good";

        try {
            $sql = "DELETE FROM $table_name
                    WHERE activity_id = :activity_id;";
            $params = [ ':activity_id' => $activity_id ];
            $stmt = $conn->prepare($sql);
            $affectedRowsNumber = $stmt->execute($params);
            if ($affectedRowsNumber > 0)
            {
                $conn = null;
                return "Sucsess deleted";
            }
        }
        catch (PDOException $e)
        {
            echo "Error: " . $e->getMessage();
            $error = "Error: ".$e->getMessage();
            $conn = null;
            return $error;
        }
    }
    else
    {
        echo "no good ".$conn->error;
    }
}
?>
