<?php

require_once 'connect.php';

// Получение данных из MySQL
function Get_from_Base($activity_data)
{
    $conn = GetConnectToDB();
    if ($conn)
    {
        echo "good ".$conn->info;
        $sql = "SELECT
                    activity_id,
                    owner_id,
                    owner_type_id,
                    type_id,
                    provider_id,
                    provider_type_id,
                    associated_entity_id,
                    subject,
                    created,
                    last_updated,
                    start_time,
                    end_time,
                    deadline,
                    completed,
                    status,
                    responsible_id,
                    priority,
                    description,
                    direction,
                    location,
                    author_id,
                    com_id,
                    com_type,
                    com_value,
                    com_entity_id,
                    com_entity_type_id,
                    com_entity_settings_fio,
                    com_entity_settings_lead_or_company_title,
                    region,
                    district,
                    locality,
                    edrpou,
                    what_interested,
                    problems,
                    service,
                    holding_name,
                    comment,
                    land_bank,
                    soil_treatment_technics,
                    spraying_technics,
                    harvesting_technics,
                    drones,
                    machinery_quality,
                    client_type_technics,
                    equipment_rental,
                    equipment_supplier,
                    equipment_availability,
                    client_type,
                    lpr_fio,
                    lpr_phone,
                    active,
                    last_date_updated
                FROM Activity;";
        if ($result = $conn->query($sql))
        {
            $conn->close();
            return $result;
        }
        else
        {
            $error = "Error: ".$conn->error;
            $conn->close();
            return $error
        }
    }
    else
    {
        echo "no good ".$conn->error;
    }
}
?>