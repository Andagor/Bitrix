<?php
require_once 'b-portal-config.php';

$today = date("d.m.Y H:i:s");
$Log = 'date => '.$today.PHP_EOL;

function get($param, $next = 0)
{
    $appParams = http_build_query(array(
        'halt' => 0,
        'cmd' => $param
    ));
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

if ($_REQUEST['auth']['application_token'] == BITRIXAPPLICATIONTOKEN)
{
    // file_put_contents('log.log', print_r($_REQUEST, true), FILE_APPEND);
    $TaskID = $_REQUEST['data']['FIELDS_BEFORE']['ID'];
    // file_put_contents('log.log', print_r("ID ".$TaskID.PHP_EOL, true), FILE_APPEND);
    $event = $_REQUEST['event'];
    $Log =  $Log.
        'application_token => '.$_REQUEST['auth']['application_token'].PHP_EOL.
        //'member_id => '.$_REQUEST['auth']['member_id'].PHP_EOL.
        'event => '.$event.PHP_EOL.
        'Task ID => '.$TaskID.PHP_EOL;

    if($event == "ONTASKUPDATE")
    {
        if($TaskID)
        {
            // Получаем тэги задачи
            $param['act'] = "task.item.gettags?".http_build_query(array(
                    'taskId' => $TaskID
                ));
            $res = get($param);
            $List_ID = $res['result']['result']['act']['0'];
            $TaskTag = $res['result']['result']['act']['1'];
            $time = $res['time']['start'];
            $Log = $Log.'List_ID => '.$List_ID.PHP_EOL.'TaskTag => '.$TaskTag.PHP_EOL;
            //file_put_contents('log.log', print_r($res, true), FILE_APPEND);
            //file_put_contents('log.log', print_r('TaskTag '.$TaskTag.PHP_EOL, true), FILE_APPEND);
            //file_put_contents('log.log', print_r('List_ID '.$List_ID.PHP_EOL, true), FILE_APPEND);
            //file_put_contents('log.log', print_r('time '.$time.PHP_EOL, true), FILE_APPEND);

            /*if (strpos($TaskTag, 'Завершена') !== false)
            {
                break;
            }*/

  	        // Получаем данные задачи
            $param['act'] = "tasks.task.get?".http_build_query(array(
                    'taskId' => $TaskID,
                    'select' => ['TITLE', 'STATUS', 'STAGE_ID', 'DESCRIPTION', 'CREATED_DATE', 'RESPONSIBLE_ID',  'CLOSED_BY', 'CLOSED_DATE', 'FORUM_ID', 'DURATION_FACT', 'TIME_SPENT_IN_LOGS', 'CHECKLIST', 'UF_CRM_TASK']
                ));
            $taskData = get($param);
            $result = $taskData['result']['result']['act'];
            // file_put_contents('log.log', print_r($taskData, true), FILE_APPEND);
            // file_put_contents('log.log', print_r($result, true), FILE_APPEND);

            $DealID             = $taskData['result']['result']['act']['task']['ufCrmTask']['0'];
            $status             = $taskData['result']['result']['act']['task']['status'];
            $stage_Id           = $taskData['result']['result']['act']['task']['stage_Id']; //361 - Сделаны
            $title              = $taskData['result']['result']['act']['task']['title'];
            $responsibleId      = $taskData['result']['result']['act']['task']['responsibleId'];
            $closedBy           = $taskData['result']['result']['act']['task']['closedBy'];
            $arCheckList        = $taskData['result']['result']['act']['task']['checklist'];
            $Log = $Log.'Данные задачи:'.PHP_EOL.
                'Deal ID => '.$DealID.PHP_EOL.
                'status => '.$status.PHP_EOL.
                'stage_Id => '.$stage_Id.PHP_EOL.
                'title => '.$title.PHP_EOL.
                'responsibleId => '.$responsibleId.PHP_EOL.
                'closedBy => '.$closedBy.PHP_EOL;
            $Log = $Log.'* ~ * ~ * ~ * ~ * ~ * ~ * ~ * ~ * ~ * ~ * ~ * ~ * ~ *'.PHP_EOL.PHP_EOL.PHP_EOL;
            file_put_contents(__DIR__.'/OnTaskUpdate_log.log', $Log, FILE_APPEND);

            /*
            $responsibleName = $taskData['result']['result']['act']['task']['responsible']['name'];
            $durationFact = $taskData['result']['result']['act']['task']['durationFact'];
            $timeSpentInLogs = $taskData['result']['result']['act']['task']['timeSpentInLogs'];
            $responsibleId = $taskData['result']['result']['act']['task']['responsibleId'];
            $forumId = $taskData['result']['result']['act']['task']['forumId'];
            */

            if ($status == 5)
            {
                if ($TaskTag == "КД_осн" || $TaskTag == "КД_доп" || $TaskTag == "Эл" || $TaskTag == "Гидро" || $TaskTag == "Гидро_Согл")
                {
                    // Получаем данные сделки
                    $param['act'] = "crm.deal.get?".http_build_query(array(
                            'id' => substr($DealID, 2)
                        ));
                    $res = get($param);

                    $Category_ID = $res['result']['result']['act']['CATEGORY_ID'];
                    // Менеджер_конст (оценка конструктора менеджеру)
                    $isGrade = $res['result']['result']['act']['UF_CRM_1638368265869'];
                    // Какая гидростанция
                    $kakaya_gidrostanciya = $res['result']['result']['act']['UF_CRM_GIDROSTANCIYA'];
                    // Гидростанция
                    $gidrostanciya = $res['result']['result']['act']['UF_CRM_1578471210052'];
                    // Гидроцилиндр
                    $hidrocylindr = $res['result']['result']['act']['UF_CRM_1574617478119'];
                    // Давление, атм
                    $davlenie = $res['result']['result']['act']['UF_CRM_DAVLENIE'];
                    // Расход, л/мин
                    $raskhod = $res['result']['result']['act']['UF_CRM_RASKHOD'];
                    // Объём рабочей жидкости, л
                    $obyomjidkosti = $res['result']['result']['act']['UF_CRM_OBYOMJIDKOSTI'];
                    // Напряжение, В
                    $napryajenie = $res['result']['result']['act']['UF_CRM_NAPRYAJENIE'];

                    // Если это задачи КД - проверяем, кто закрыл
                    if ($TaskTag == "КД_осн" || $TaskTag == "КД_доп")
                    {
                        // Если задачу закрыл не ответственный конструктор
                        if ($closedBy != $responsibleId && $closedBy != 259)
                        {
                            // Возобновляем задачу
                            $param['act'] = "tasks.task.update?".http_build_query(array(
                                    'taskId' => $TaskID,
                                    'fields' => array(
                                        'STATUS' => 2,
                                        'STAGE_ID' => 'NEW'
                                )));
                            $res = get($param);

                            // Добавляем в задачу комментарий
                            $param['act'] = "task.commentitem.add?".http_build_query(array(
                                    'id' => $TaskID,
                                    'fields' => array(
                                        'POST_MESSAGE' => 'Задача возобновлена по причине: [color=red][b]задачу закрыл не ответственный конструктор[/b][/color]',
                                        'AUTHOR_ID' => 259
                                )));
                            $res = get($param);
                            exit;
                        }

                        // Если сделка направления Продажа
                        if ($Category_ID == 0)
                        {
                            // Если не поставил оценку
                            if ( empty($isGrade) )
                            {
                                // Возобновляем задачу
                                $param['act'] = "tasks.task.update?".http_build_query(array(
                                        'taskId' => $TaskID,
                                        'fields' => array(
                                            'STATUS' => 2,
                                            'STAGE_ID' => 'NEW'
                                    )));
                                $res = get($param);

                                // Сообщение конструктору
                                $param['act'] = "im.notify?".http_build_query(array(
                                      "to" => $responsibleId,
                                      "message" => "[size=3][color=red][b]Внимание![/b][/color][/size]#BR#Вы не поставили оценку проекту#BR#Для завершения задачи [color=red][b]необходимо заполнить следующие поля сделки:[/color][/b]#BR#[b]- Менеджер_конст[/b]",
                                      "type" => "SYSTEM USER",
                                      ));
                                $Result = get($param);

                                // Добавляем в задачу комментарий
                                $param['act'] = "task.commentitem.add?".http_build_query(array(
                                        'id' => $TaskID,
                                        'fields' => array(
                                            'POST_MESSAGE' => 'Задача возобновлена по причине: [color=red][b]не заполнены необходимые поля в сделке[/b][/color]. Для завершения задачи необходимо заполнить следующие поля сделки:
                                                - Менеджер_конст',
                                            'AUTHOR_ID' => 259
                                    )));
                                $res = get($param);
                                exit;
                            }
                            // Если гидростанция = Не стандартная (1514 = Стандартная)
                            /*if ($kakaya_gidrostanciya == 1516)
                            {
                                // Если не заполнены поля Данные для подбора ГС и Гидроцилиндр
                                if ( empty($hidrocylindr) || empty($davlenie) || empty($raskhod) || empty($obyomjidkosti) || empty($napryajenie) )
                                {
                                    // Возобновляем задачу
                                    $param['act'] = "tasks.task.update?".http_build_query(array(
                                            'taskId' => $TaskID,
                                            'fields' => array(
                                                'STATUS' => 2,
                                                'STAGE_ID' => 'NEW'
                                        )));
                                    $res = get($param);

                                    // Сообщение конструктору
                                    $param['act'] = "im.notify?".http_build_query(array(
                                          "to" => $responsibleId,
                                          "message" => "[size=3][color=red][b]Внимание![/b][/color][/size]#BR#Вы не заполнили необходимые поля в сделке#BR#Для завершения задачи [color=red][b]необходимо заполнить следующие поля сделки:[/color][/b][b]#BR#- Гидроцилиндр#BR#- Давление, атм#BR#- Расход, л/мин#BR#- Объём рабочей жидкости, л#BR#- Напряжение, В[/b]",
                                          "type" => "SYSTEM USER",
                                          ));
                                    $Result = get($param);

                                    // Добавляем в задачу комментарий
                                    $param['act'] = "task.commentitem.add?".http_build_query(array(
                                            'id' => $TaskID,
                                            'fields' => array(
                                                'POST_MESSAGE' => 'Задача возобновлена по причине: [color=red][b]не заполнены необходимые поля в сделке[/b][/color]. Для завершения задачи необходимо заполнить следующие поля сделки:
                                                    - Гидроцилиндр
                                                    - Давление, атм
                                                    - Расход, л/мин
                                                    - Объём рабочей жидкости, л
                                                    - Напряжение, В',
                                                'AUTHOR_ID' => 259
                                        )));
                                    $res = get($param);
                                    exit;
                                }
                            }*/
                        }
                    }

                    // Если это задача Гидрочасть
                    if ($TaskTag == "Гидро_Согл")
                    {
                        // Если сделка направления Продажа
                        if ($Category_ID == 0)
                        {
                            // Если не поставили Гидростанция
                            if ( empty($gidrostanciya) )
                            {
                                // Возобновляем задачу
                                $param['act'] = "tasks.task.update?".http_build_query(array(
                                        'taskId' => $TaskID,
                                        'fields' => array(
                                            'STATUS' => 2,
                                            'STAGE_ID' => 'NEW'
                                    )));
                                $res = get($param);

                                // Сообщение ответственному за задачу
                                $param['act'] = "im.notify?".http_build_query(array(
                                      "to" => $responsibleId,
                                      "message" => "[size=3][color=red][b]Внимание![/b][/color][/size]#BR#Вы не заполнили поле Гидростанция#BR#Для завершения задачи [color=red][b]необходимо заполнить следующие поля сделки:[/color][/b]#BR#[b]- Гидростанция[/b]",
                                      "type" => "SYSTEM USER",
                                      ));
                                $Result = get($param);

                                // Добавляем в задачу комментарий
                                $param['act'] = "task.commentitem.add?".http_build_query(array(
                                        'id' => $TaskID,
                                        'fields' => array(
                                            'POST_MESSAGE' => 'Задача возобновлена по причине: [color=red][b]не заполнены необходимые поля в сделке[/b][/color]. Для завершения задачи необходимо заполнить следующие поля сделки:
                                                - Гидростанция',
                                            'AUTHOR_ID' => 259
                                    )));
                                $res = get($param);
                                exit;
                            }
                        }
                    }

/*                    // Если тэг КД_доп и закрыты не все чек-листы
                    if ($TaskTag == "КД_доп")
                    {
                        if ($arCheckList)
                        {
                            foreach ($arCheckList as $checkID => $checkValue)
                            {
                                if ($checkValue['isComplete'] == 'N' && $checkValue['parentId'] != 0)
                                {
                                    //Возобновляем задачу
                                    $param['act'] = "tasks.task.update?".http_build_query(array(
                                            'taskId' => $TaskID,
                                            'fields' => array(
                                                'STATUS' => 2
                                        )));
                                    $res = get($param);
                                    exit;
                                }
                            }
                        }
                    }*/
                }

                switch ($TaskTag)
                {
                    case "КД_осн":
				        $param['act'] = "bizproc.workflow.start?".http_build_query(array(
        		                "TEMPLATE_ID" => 247,
        		                "DOCUMENT_ID" => ['lists', 'Bitrix\Lists\BizprocDocumentLists', $List_ID],
        		                "PARAMETERS" => ["WhatTask" => 1, "Tag" => "КД_осн", "ListID" => $List_ID, "Flag" => $time]
                            ));
		                $BPResult = get($param);
                    break;
                    case "КД_доп":
				        $param['act'] = "bizproc.workflow.start?".http_build_query(array(
        		                "TEMPLATE_ID" => 247,
        		                "DOCUMENT_ID" => ['lists', 'Bitrix\Lists\BizprocDocumentLists', $List_ID],
        		                "PARAMETERS" => ["WhatTask" => 2, "Tag" => "КД_доп", "ListID" => $List_ID, "Flag" => $time]
    		                ));
		                $BPResult = get($param);
                    break;
                    /*case "Эл":
				        $param['act'] = "bizproc.workflow.start?".http_build_query(array(
        		                "TEMPLATE_ID" => 247,
        		                "DOCUMENT_ID" => ['lists', 'Bitrix\Lists\BizprocDocumentLists', $List_ID],
        		                "PARAMETERS" => ["WhatTask" => 3, "Tag" => "Эл", "ListID" => $List_ID, "Flag" => $time]
    		                ));
		                $BPResult = get($param);
                    break;*/
                    /*case "Гидро":
				        $param['act'] = "bizproc.workflow.start?".http_build_query(array(
        		                "TEMPLATE_ID" => 247,
        		                "DOCUMENT_ID" => ['lists', 'Bitrix\Lists\BizprocDocumentLists', $List_ID],
        		                "PARAMETERS" => ["WhatTask" => 4, "Tag" => "Гидро", "ListID" => $List_ID, "Flag" => $time]
    		                ));
		                $BPResult = get($param);
            		break;*/
                    case "Гидро_Согл":
                        $param['act'] = "bizproc.workflow.start?".http_build_query(array(
                                "TEMPLATE_ID" => 247,
                                "DOCUMENT_ID" => ['lists', 'Bitrix\Lists\BizprocDocumentLists', $List_ID],
                                "PARAMETERS" => ["WhatTask" => 5, "Tag" => "Гидро_Согл", "ListID" => $List_ID, "Flag" => $time]
                            ));
                        $BPResult = get($param);
                    break;
                    default:
                        // file_put_contents('log.log', print_r('Это не задача конструктору '.$title.PHP_EOL, true), FILE_APPEND);
                    break;
                }
            }
        }
    }
}
?>