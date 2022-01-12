<?php
require_once 'connect.php';

// Добавление данных в MySQL
function Add_to_Base($activity_data, $table_name)
{
    $conn = GetConnectToDB();

        try {
            $sql = ("INSERT INTO $table_name (activity_id, owner_id, owner_type_id, type_id, provider_id, provider_type_id, associated_entity_id, subject, description, created, last_updated, start_time, end_time, deadline, completed, status, author_id, responsible_id, priority, direction, location, com_id, com_type, com_value, com_entity_id, com_entity_type_id, client_fio, lead_or_company_title, region, district, locality, edrpou, what_interested, problems, service, holding_name, comment, land_bank, soil_treatment_technics, spraying_technics, harvesting_technics, drones, machinery_quality, client_type_technics, equipment_rental, equipment_supplier, equipment_availability, client_type, lpr_fio, lpr_phone, last_date_updated) VALUES (:activity_id, :owner_id, :owner_type_id, :type_id, :provider_id, :provider_type_id, :associated_entity_id, :subject, :description, :created, :last_updated, :start_time, :end_time, :deadline, :completed, :status, :responsible_id, :priority, :direction, :location, :author_id, :com_id, :com_type, :com_value, :com_entity_id, :com_entity_type_id, :client_fio, :lead_or_company_title, :region, :district, :locality, :edrpou, :what_interested, :problems, :service, :holding_name, :comment, :land_bank, :soil_treatment_technics, :spraying_technics, :harvesting_technics, :drones, :machinery_quality, :client_type_technics, :equipment_rental, :equipment_supplier, :equipment_availability, :client_type, :lpr_fio, :lpr_phone, :last_date_updated)");

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
                return "Sucsess added";
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
?>
