<?php

require_once 'connect.php';

// Обновление данных в MySQL
function Update_in_Base($activity_data, $table_name)
{
    $conn = GetConnectToDB();
    if ($conn)
    {
        echo "good ".$conn->info;
        try {
            $sql = "UPDATE $table_name
                    SET
                        owner_id = :owner_id,
                        owner_type_id = :owner_type_id,
                        type_id = :type_id,
                        provider_id = :provider_id,
                        provider_type_id = :provider_type_id,
                        associated_entity_id = :associated_entity_id,
                        subject = :subject,
                        description = :description,
                        created = :created,
                        last_updated = :last_updated,
                        start_time = :start_time,
                        end_time = :end_time,
                        deadline = :deadline,
                        completed = :completed,
                        status = :status,
                        responsible_id = :responsible_id,
                        priority = :priority,
                        direction = :direction,
                        location = :location,
                        author_id = :author_id,
                        com_id = :com_id,
                        com_type = :com_type,
                        com_value = :com_value,
                        com_entity_id = :com_entity_id,
                        com_entity_type_id = :com_entity_type_id,
                        client_fio = :client_fio,
                        lead_or_company_title = :lead_or_company_title,
                        region = :region,
                        district = :district,
                        locality = :locality,
                        edrpou = :edrpou,
                        what_interested = :what_interested,
                        problems = :problems,
                        service = :service,
                        holding_name = :holding_name,
                        comment = :comment,
                        land_bank = :land_bank,
                        soil_treatment_technics = :soil_treatment_technics,
                        spraying_technics = :spraying_technics,
                        harvesting_technics = :harvesting_technics,
                        drones = :drones,
                        machinery_quality = :machinery_quality,
                        client_type_technics = :client_type_technics,
                        equipment_rental = :equipment_rental,
                        equipment_supplier = :equipment_supplier,
                        equipment_availability = :equipment_availability,
                        client_type = :client_type,
                        lpr_fio = :lpr_fio,
                        lpr_phone = :lpr_phone,
                        last_date_updated = :last_date_updated
                    WHERE activity_id = :activity_id;";

            $params = [ ':activity_id' => $activity_data['activity_id'],
                        ':owner_id' => $activity_data['owner_id'],
                        ':owner_type_id' => $activity_data['owner_type_id'],
                        ':type_id' => $activity_data['activity_type'],
                        ':provider_id' => $activity_data['provider_id'],
                        ':provider_type_id' => $activity_data['provider_type_id'],
                        ':associated_entity_id' => $activity_data['associated_entity_id'],
                        ':subject' => $activity_data['subject'],
                        ':description' => $activity_data['description'],
                        ':created' => $activity_data['date_create'],
                        ':last_updated' => $activity_data['last_updated'],
                        ':start_time' => $activity_data['start_time'],
                        ':end_time' => $activity_data['end_time'],
                        ':deadline' => $activity_data['deadline'],
                        ':completed' => $activity_data['completed'],
                        ':status' => $activity_data['status'],
                        ':responsible_id' => $activity_data['author_id'],
                        ':priority' => $activity_data['responsible_id'],
                        ':direction' => $activity_data['priority'],
                        ':location' => $activity_data['direction'],
                        ':author_id' => $activity_data['location'],
                        ':com_id' => $activity_data['com_id'],
                        ':com_type' => $activity_data['com_type'],
                        ':com_value' => $activity_data['com_value'],
                        ':com_entity_id' => $activity_data['com_entity_id'],
                        ':com_entity_type_id' => $activity_data['com_entity_type_id'],
                        ':client_fio' => $activity_data['client_fio'],
                        ':lead_or_company_title' => $activity_data['lead_or_company_title'],
                        ':region' => $activity_data['region'],
                        ':district' => $activity_data['district'],
                        ':locality' => $activity_data['locality'],
                        ':edrpou' => $activity_data['edrpou'],
                        ':what_interested' => $activity_data['what_interested'],
                        ':problems' => $activity_data['problems'],
                        ':service' => $activity_data['service'],
                        ':holding_name' => $activity_data['holding_name'],
                        ':comment' => $activity_data['comment'],
                        ':land_bank' => $activity_data['land_bank'],
                        ':soil_treatment_technics' => $activity_data['soil_treatment_technics'],
                        ':spraying_technics' => $activity_data['spraying_technics'],
                        ':harvesting_technics' => $activity_data['harvesting_technics'],
                        ':drones' => $activity_data['drones'],
                        ':machinery_quality' => $activity_data['machinery_quality'],
                        ':client_type_technics' => $activity_data['client_type_technics'],
                        ':equipment_rental' => $activity_data['equipment_rental'],
                        ':equipment_supplier' => $activity_data['equipment_supplier'],
                        ':equipment_availability' => $activity_data['equipment_availability'],
                        ':client_type' => $activity_data['client_type'],
                        ':lpr_fio' => $activity_data['lpr_fio'],
                        ':lpr_phone' => $activity_data['lpr_phone'],
                        ':last_date_updated' => $activity_data['last_date_updated']
            ];
            $stmt = $conn->prepare($sql);
            $affectedRowsNumber = $stmt->execute($params);
            if ($affectedRowsNumber > 0)
            {
                $conn = null;
                return "Sucsess updated";
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