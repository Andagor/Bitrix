<?php

require_once '../app_config.php';

function get($param, $next = 0)
{
    $appParams = http_build_query(array('halt' => 0, 'cmd' => $param));
    $appRequestUrl = BITRIXWEBHOOK;
    $curl=curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $appRequestUrl,
        CURLOPT_POSTFIELDS => $appParams
    ));
    $out=curl_exec($curl);
    curl_close($curl);
    $result = json_decode($out, 1);
    return $result;
}

// Названия значений списочных полей компаний
function GetCompanyListFieldNames($field_ID)
{
    if ($field_ID)
    {
        $param['act'] = "crm.company.userfield.get?".http_build_query(array(
                'id' => $field_ID
            ));
        $field_result = get($param);

        if ($field_result)
        {
            $result = $field_result['result']['result']['act']['LIST'];
            foreach ($result as $key => $value)
            {
                $arListFieldNames[$value['ID']] = $value['VALUE'];
            }
            return $arListFieldNames;
        }
        else
        {
            return 'Error: empty field values';
        }
    }
    else
    {
        return 'Error: no field ID values';
    }
}

// Типы дел
function GetActivityTypes()
{
    $param['act'] = "crm.enum.activitytype?";
    $Result = get($param);

    if ($Result)
    {
        $activitytyperes = $Result['result']['result']['act'];
        foreach ($activitytyperes as $id => $value)
        {
            $arActivity_Types[$value['ID']] = $value['NAME'];
        }
        return $arActivity_Types;
    }
    else
    {
        return 'Error: no activity types values';
    }
}

// Статусы дел
function GetActivityStatuses()
{
    $param['act'] = "crm.enum.activitystatus?";
    $Result = get($param);

    if ($Result)
    {
        $activitystatuses = $Result['result']['result']['act'];
        foreach ($activitystatuses as $id => $value)
        {
            $arActivity_Statuses[$value['ID']] = $value['NAME'];
        }
        return $arActivity_Statuses;
    }
    else
    {
        return 'Error: no activity statuses values';
    }
}

// ФИО пользователей
function getUserName($userID)
{
    $param['act'] = "user.get?".http_build_query(array(
            "ID" => $userID
        ));
    $Result = get($param);

    $error = $Result['result']['result_error']['error'];
    $error_description = $Result['result']['result_error']['error_description'];

    if ($error)
    {
        return 'QUERY_LIMIT_EXCEEDED';
    }
    if ($Result)
    {
        $FIO = $Result['result']['result']['act']['0']['LAST_NAME'].' '.$Result['result']['result']['act']['0']['NAME']; // .' '.$Result['result']['result']['act']['0']['SECOND_NAME']
        return $FIO;
    }
    else
    {
        return false;
    }
}

// Данные компании
function GetCompanyData($Company_ID)
{
    $param['act'] = "crm.company.list?".http_build_query(array(
                'order'  => ['ID' => 'ASC'],
                'filter' => ['ID' => $Company_ID, 'CHECK_PERMISSIONS' => 'N'],
                'select' => ['UF_CRM_60B6BD6D977E1', 'UF_CRM_61656092693D6', 'UF_CRM_1618489024', 'UF_CRM_1601894611803', 'UF_CRM_5EFAF57C3534D', 'UF_CRM_1602492629643', 'UF_CRM_1602492557116', 'UF_CRM_61853DE5F164B', 'UF_CRM_1618489015', 'UF_CRM_1602491193542', 'UF_CRM_1636124165489', 'UF_CRM_1636124224003', 'UF_CRM_1636124591046', 'UF_CRM_1636124636305', 'UF_CRM_1602491787722', 'UF_CRM_1602491844146', 'UF_CRM_1602491892433', 'UF_CRM_1602492583103', 'UF_CRM_1602491680257', 'UF_CRM_5EFAF57C44BAF', 'UF_CRM_FIO_LPR', 'UF_CRM_PHONE_LPR'],
                'start' => -1
        ));
    $res = get($param);

    $result = $res['result']['result']['act']['0'];
    $error = $res['result']['result_error']['error'];
    $error_description = $res['result']['result_error']['error_description'];

    if ($error)
    {
        return 'QUERY_LIMIT_EXCEEDED';
    }
    if ($result)
    {
        // Получаем значения списочных полей
        $arRegionName = GetCompanyListFieldNames(2360);
        $arDistrictName = GetCompanyListFieldNames(2448);
        $arWhatInterested = GetCompanyListFieldNames(266);
        $arSoilTreatmentTechnics = GetCompanyListFieldNames(2610);
        $arSprayingTechnics = GetCompanyListFieldNames(2612);
        $arHarvestingTechnics = GetCompanyListFieldNames(2614);
        $arDrones = GetCompanyListFieldNames(2616);
        $arMachineryQuality = GetCompanyListFieldNames(532);
        $arClientTypeTechnics = GetCompanyListFieldNames(534);
        $arEquipmentRental = GetCompanyListFieldNames(536);
        $arEquipmentAvailability = GetCompanyListFieldNames(530);
        $arСlientType = GetCompanyListFieldNames(268);

        sleep(30);

// Заполняем массив
$arCompany_Data['region']                   = $arRegionName[$result['UF_CRM_60B6BD6D977E1']];
$arCompany_Data['district']                 = $arDistrictName[$result['UF_CRM_61656092693D6']];
$arCompany_Data['land_bank']                = $result['UF_CRM_1602491193542'];
$arCompany_Data['edrpou']                   = $result['UF_CRM_1601894611803'];
$arCompany_Data['locality']                 = $result['UF_CRM_1618489024'];
foreach ($result['UF_CRM_5EFAF57C3534D'] as $key => $value)
{
    $WhatInterested[] = $arWhatInterested[$value];
}

$arCompany_Data['what_interested']          = implode(', ', $WhatInterested); // $arWhatInterested[$result['UF_CRM_5EFAF57C3534D']];
$arCompany_Data['problems']                 = $result['UF_CRM_1602492629643'];
$arCompany_Data['service']                  = $result['UF_CRM_1602492557116'];
$arCompany_Data['holding_name']             = $result['UF_CRM_61853DE5F164B'];
$arCompany_Data['comment']                  = $result['UF_CRM_1618489015'];
$arCompany_Data['soil_treatment_technics']  = $arSoilTreatmentTechnics[$result['UF_CRM_1636124165489']];
$arCompany_Data['spraying_technics']        = $arSprayingTechnics[$result['UF_CRM_1636124224003']];
$arCompany_Data['harvesting_technics']      = $arHarvestingTechnics[$result['UF_CRM_1636124591046']];
$arCompany_Data['drones']                   = $arDrones[$result['UF_CRM_1636124636305']];
$arCompany_Data['machinery_quality']        = $arMachineryQuality[$result['UF_CRM_1602491787722']];
$arCompany_Data['client_type_technics']     = $arClientTypeTechnics[$result['UF_CRM_1602491844146']];
$arCompany_Data['equipment_rental']         = $arEquipmentRental[$result['UF_CRM_1602491892433']];
$arCompany_Data['equipment_supplier']       = $result['UF_CRM_1602492583103'];
$arCompany_Data['equipment_availability']   = $arEquipmentAvailability[$result['UF_CRM_1602491680257']];
$arCompany_Data['client_type']              = $arСlientType[$result['UF_CRM_5EFAF57C44BAF']];
$arCompany_Data['lpr_fio']                  = $result['UF_CRM_FIO_LPR'];
$arCompany_Data['lpr_phone']                = implode(", ", $result['UF_CRM_PHONE_LPR']);
        return $arCompany_Data;
    }
    else
    {
        return false;
    }
}

// Форматирование даты
function FormatDate($date)
{
    $new_date = new DateTime($date);
    $formated_date = $new_date->format("Y-m-d H:i:s+0300");
    return $formated_date;
}

function GetActivityData($activity_ID)
{
    $param['act'] = "crm.activity.list?".http_build_query(array(
            "order" => ['ID' => "ASC"],
            "filter" => [
                    'ID' => $activity_ID,
                    'CHECK_PERMISSIONS' => 'N'
                ],
            "select" => [ "*", "COMMUNICATIONS" ],
            "start" => -1
        ));
    $ResultActivity = get($param);

    if ($ResultActivity)
    {
        $arActivities = $ResultActivity['result']['result']['act'];
        foreach ($arActivities as $key => $value)
        {
            $Lead_ID = '';
            $Contact_ID = '';
            $Company_ID = '';
            $Lead_Name = '';
            $Company_Name = '';
            $Client_Name = '';
            $Company_Data = '';

            if ($value['COMMUNICATIONS']['0']['ENTITY_TYPE_ID'] == 1) // Лид
            {
                $Lead_ID =      $value['COMMUNICATIONS']['0']['ENTITY_ID'];
                $Client_Name =  $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['NAME'].' '.
                                $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['SECOND_NAME'].' '.
                                $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['LAST_NAME'];
                $Lead_Name =    $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['LEAD_TITLE'];
            }
            if ($value['COMMUNICATIONS']['0']['ENTITY_TYPE_ID'] == 3) // Контакт
            {
                $Contact_ID =   $value['COMMUNICATIONS']['0']['ENTITY_ID'];
                $Client_Name =  $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['NAME'].' '.
                                $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['SECOND_NAME'].' '.
                                $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['LAST_NAME'];
                $Company_Name = $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['COMPANY_TITLE'];
                $Company_ID =   $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['COMPANY_ID'];
                $Company_Data = GetCompanyData($Company_ID);
                $check_Company_Data = 0;
                while ($check_Company_Data == 0)
                {
                    if ($Company_Data == 'QUERY_LIMIT_EXCEEDED')
                    {
                      sleep(10);
                      $Company_Data = GetCompanyData($Company_ID);
                    }
                    if (empty($Company_Data) && $Company_Data != false)
                    {
                      sleep(1);
                    }
                    else
                    {
                      $check_Company_Data = 1;
                    }
                }
            }
            if ($value['COMMUNICATIONS']['0']['ENTITY_TYPE_ID'] == 4) // Компания
            {
                $Company_ID =   $value['COMMUNICATIONS']['0']['ENTITY_ID'];
                $Company_Name = $value['COMMUNICATIONS']['0']['ENTITY_SETTINGS']['COMPANY_TITLE'];
                $Company_Data = GetCompanyData($Company_ID);
                $check_Company_Data = 0;
                while ($check_Company_Data == 0)
                {
                    if ($Company_Data == 'QUERY_LIMIT_EXCEEDED')
                    {
                      sleep(10);
                      $Company_Data = GetCompanyData($Company_ID);
                    }
                    if (empty($Company_Data) && $Company_Data != false)
                    {
                      sleep(1);
                    }
                    else
                    {
                      $check_Company_Data = 1;
                    }
                }
            }

            $User_Name = getUserName($value['RESPONSIBLE_ID']);
            $check_User_Name = 0;
            while ($check_User_Name == 0)
            {
                if ($User_Name == 'QUERY_LIMIT_EXCEEDED')
                {
                  sleep(10);
                  $User_Name = getUserName($value['RESPONSIBLE_ID']);
                }
                if (empty($User_Name) && $User_Name != false)
                {
                    sleep(1);
                }
                else
                {
                    $check_User_Name = 1;
                }
            }

            $Author_Name = getUserName($value['AUTHOR_ID']);
            $check_Author_Name = 0;
            while ($check_Author_Name == 0)
            {
                if ($Author_Name == 'QUERY_LIMIT_EXCEEDED')
                {
                  sleep(10);
                  $Author_Name = getUserName($value['AUTHOR_ID']);
                }
                if (empty($Author_Name) && $Author_Name != false)
                {
                    sleep(1);
                }
                else
                {
                    $check_Author_Name = 1;
                }
            }

            $arActivity_Types = GetActivityTypes();
            $arActivity_Statuses = GetActivityStatuses();

            switch ($value['OWNER_TYPE_ID'])
            {
                case 1:
                    $owner_type = 'Лід';
                    break;
                case 2:
                    $owner_type = 'Угода';
                    break;
                case 3:
                    $owner_type = 'Контакт';
                    break;
                default:
                    $owner_type = 'Компанія';
                    break;
            }

            $Entity = [
                    'activity_id' => $value['ID'],
                    'owner_id' => $value['OWNER_ID'],
                    'owner_type_id' => $owner_type,
                    'activity_type' => $arActivity_Types[$value['TYPE_ID']],
                    'provider_id' => $value['PROVIDER_ID'],
                    'provider_type_id' => $value['PROVIDER_TYPE_ID'],
                    'associated_entity_id' => $value['ASSOCIATED_ENTITY_ID'],
                    'subject' => $value['SUBJECT'],
                    'description' => $value['DESCRIPTION'],
                    'date_create' => FormatDate($value['CREATED']),
                    'last_updated' => FormatDate($value['LAST_UPDATED']),
                    'start_time' => FormatDate($value['START_TIME']),
                    'end_time' => FormatDate($value['END_TIME']),
                    'deadline' => FormatDate($value['DEADLINE']),
                    'completed' => ($value['COMPLETED'] == 'Y') ? 'Виконана' : 'Не виконана',
                    'status' => $arActivity_Statuses[$value['STATUS']],
                    'author_id' => $Author_Name,
                    'responsible_id' => $User_Name,
                    'priority' => ($value['PRIORITY'] == 2) ? 'Звичайна' : 'Важлива',
                    'direction' => ($value['DIRECTION'] == 1) ? 'Вхідне' : ($value['DIRECTION'] == 2) ? 'Вихідне' : '',
                    'location' => $value['LOCATION'],
                    'com_id' => $value['COMMUNICATIONS_ID'],
                    'com_type' => $value['COMMUNICATIONS_TYPE'],
                    'com_value' => $value['COMMUNICATIONS_VALUE'],
                    'com_entity_id' => $value['COMMUNICATIONS_ENTITY_ID'],
                    'com_entity_type_id' => $value['COMMUNICATIONS_ENTITY_TYPE_ID'],
                    'client_fio' => $Client_Name, // com_entity_settings_fio
                    'lead_or_company_title' => $Company_Name, // com_entity_settings_lead_or_company_title
                    'region' => $Company_Data['region'],
                    'district' => $Company_Data['district'],
                    'locality' => $Company_Data['locality'],
                    'edrpou' => $Company_Data['edrpou'],
                    'what_interested' => $Company_Data['what_interested'],
                    'problems' => $Company_Data['problems'],
                    'service' => $Company_Data['service'],
                    'holding_name' => $Company_Data['holding_name'],
                    'comment' => $Company_Data['comment'],
                    'land_bank' => $Company_Data['land_bank'],
                    'soil_treatment_technics' => $Company_Data['soil_treatment_technics'],
                    'spraying_technics' => $Company_Data['spraying_technics'],
                    'harvesting_technics' => $Company_Data['harvesting_technics'],
                    'drones' => $Company_Data['drones'],
                    'machinery_quality' => $Company_Data['machinery_quality'],
                    'client_type_technics' => $Company_Data['client_type_technics'],
                    'equipment_rental' => $Company_Data['equipment_rental'],
                    'equipment_supplier' => $Company_Data['equipment_supplier'],
                    'equipment_availability' => $Company_Data['equipment_availability'],
                    'client_type' => $Company_Data['client_type'],
                    'lpr_fio' => $Company_Data['lpr_fio'],
                    'lpr_phone' => $Company_Data['lpr_phone'],
                    'last_date_updated' => date('Y-m-d H:i:s+0300')
                ];
            return $Entity;
        }
    }
    else
    {
        return 'Error: no found activity with ID '.$activity_ID;
    }
}
?>