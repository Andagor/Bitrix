<?php

// --- НАСТРОЙКИ ---
$webhookUrl = 'https://portal/rest/777/7777777/'; // URL вебхука
$statusField = 'UF_CRM_1754989884'; // поле Статус клиента в контакте
$countField = 'UF_CRM_SUCCESS_DEAL_QUANTITY'; // поле Количество успешных заказов в контакте
$summField = 'UF_CRM_SUCCESS_DEAL_SUMM'; // поле Сумма успешных заказов в контакте
$dateField = 'UF_CRM_SUCCESS_DEAL_LAST_DATE'; // поле Дата последнего заказа в контакте
$vipStatusId = 258; // ID значения "VIP"
$activeStatusId = 388; // ID значения "Активний"
$usualyStatusId = 430; // ID значения "Звичайний"

$limit = 50; // Сколько контактов берем за один запуск крона
$stateFile = __DIR__ . '/last_contact_id.txt'; // Файл для хранения прогресса
$logFile = __DIR__ . '/calc_log.json';

// Источники, которые мы игнорируем
$ignoredSources = [
    '14', 			// Eva
	'19', 			// Kasta
	'23', 			// Miorro.B2B: Заказ
	'16', 			// Miorro.com: Заказ
	'22', 			// Miorro.onlin: Заказ
	'24', 			// Miorro.onlin: Форма обратного звонка
	'21', 			// Miorro: Instagram
	'18', 			// Miorro: Звонок
	'17', 			// Miorro: Форма обратного звонка
	'10', 			// Алло
	'BOOKING', 		// Дропшипинг
	'11', 			// Интертоп
	'REPEAT_SALE', 	// Мономаркет
	'12', 			// Пром
	'9', 			// Розетка
	'20', 			// Розетка: Lumispace
	'8' 			// Эпицентр
];

$lockFile = fopen(__DIR__ . '/script.lock', 'w');

// Пытаемся эксклюзивно заблокировать файл (LOCK_EX). 
// Флаг LOCK_NB говорит "не жди, если файл уже занят, а сразу возвращай false"
if (!flock($lockFile, LOCK_EX | LOCK_NB)) {
    echo "Скрипт уже запущен в другом процессе. Ждем следующего запуска по крону.";
    exit;
}

// Границы предыдущего месяца в формате ISO 8601 для Битрикс24
$prevMonthStart = date('Y-m-d\T00:00:00', strtotime('first day of last month'));
$prevMonthEnd   = date('Y-m-d\T23:59:59', strtotime('last day of last month'));

// Получаем ID контакта, на котором остановились в прошлый раз
$lastId = file_exists($stateFile) ? (int)file_get_contents($stateFile) : 0;

// --- ФУНКЦИЯ ДЛЯ ЗАПРОСОВ К API ---
function callBitrix24($method, $params, $url) {
    $queryUrl = $url . $method . '.json';
    $queryData = http_build_query($params);
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ]);
    
    $result = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($result, true);
}

// 1. Получаем пачку контактов
$contactsData = callBitrix24('crm.contact.list', [
    'order' => ['ID' => 'ASC'],
    'filter' => ['>ID' => $lastId],
    'select' => ['ID', $statusField, $countField, $summField, $dateField],
    'limit' => $limit,
    'start' => -1
], $webhookUrl);

if (empty($contactsData['result'])) {
    echo "Все контакты обработаны. Можно сбрасывать счетчик.";
    // unlink($stateFile); // Раскомментируй, если нужно начинать заново автоматически
    exit;
}

$processedCount = 0;
$highestId = $lastId;

// 2. Готовим BATCH-запрос для получения сделок
$batchDealsCmd = [];
foreach ($contactsData['result'] as $contact) {
    $cId = $contact['ID'];
    $highestId = $cId;
    
    // Формируем строку запроса для каждого контакта
    $batchDealsCmd['deals_' . $cId] = 'crm.deal.list?' . http_build_query([
        'filter' => [
            'CONTACT_ID' => $cId,
            'STAGE_SEMANTIC_ID' => 'S',
            'CATEGORY_ID' => 0
        ],
        'select' => ['ID', 'OPPORTUNITY', 'BEGINDATE', 'CLOSEDATE', 'SOURCE_ID'],
        'start' => -1
    ]);
}

// Отправляем BATCH-запрос (Запрос 2 - получаем сделки сразу для всех 50 контактов)
$allDealsData = callBitrix24('batch', ['halt' => 0, 'cmd' => $batchDealsCmd], $webhookUrl);
$dealsResults = $allDealsData['result']['result'] ?? [];

$batchUpdateCmd = [];
$processedCount = 0;
    
// 3. Локально обрабатываем данные
foreach ($contactsData['result'] as $contact) {
    $contactId = $contact['ID'];
    $currentStatus = $contact[$statusField] ?? '';
    $currentCount  = (int)($contact[$countField] ?? 0);
    $currentSumm   = (float)($contact[$summField] ?? 0);
    $currentDate   = $contact[$dateField] ?? '';
    
    // Достаем сделки конкретного контакта из результатов BATCH-запроса
    $deals = $dealsResults['deals_' . $contactId] ?? [];
    
    $isVip = false;
    $totalSum = 0;
    $totalCount = 0;
    $lastMonthCount = 0;
    $maxDealDate = '';
    
    foreach ($deals as $deal) {
        if (in_array($deal['SOURCE_ID'], $ignoredSources)) continue;
        
        $amount = (float)$deal['OPPORTUNITY'];
        $currentDealDate = $deal['CLOSEDATE'];
        
        $totalSum += $amount;
        $totalCount++;
        
        if (empty($maxDealDate) || $currentDealDate > $maxDealDate) {
            $maxDealDate = $currentDealDate;
        }
        
        if ($amount >= 5000) $isVip = true;
        
        if ($currentDealDate >= $prevMonthStart && $currentDealDate <= $prevMonthEnd) {
            $lastMonthCount++;
        }
    }
    
    // Определяем статус
    $newStatus = '';
    if ($isVip) {
        $newStatus = $vipStatusId;
    } elseif ($totalSum >= 10000 || $lastMonthCount >= 5) {
        $newStatus = $activeStatusId;
    } else {
        $newStatus = $usualyStatusId;
    }

    // --- ЛОГИРОВАНИЕ ---
    $logData = [
        'timestamp'        => date('Y-m-d H:i:s'),
        'contact_id'       => $contactId,
        'total_deals'      => $totalCount,
        'total_sum'        => $totalSum,
        'last_deal_date'   => $maxDealDate,
        'last_month_count' => $lastMonthCount,
        'is_vip_amount'    => $isVip,
        'old_status'       => $currentStatus,
        'new_status'       => $newStatus,
        // Опционально: можно записывать причину смены статуса
        'status_reason'    => $isVip ? 'Разовая покупка >= 5000' : 
                              ($totalSum >= 10000 ? 'Общая сумма >= 10000' : 
                              ($lastMonthCount >= 5 ? '5+ покупок за месяц' : 'Не дотянул'))
    ];

    // Кодируем в JSON. 
    // JSON_UNESCAPED_UNICODE нужен, чтобы кириллица (например, в status_reason) 
    // оставалась читаемой, а не превращалась в \u0410...
    $jsonString = json_encode($logData, JSON_UNESCAPED_UNICODE) . PHP_EOL;

    // Пишем в файл с флагами добавления в конец и блокировки файла на момент записи
    file_put_contents($logFile, $jsonString, FILE_APPEND | LOCK_EX);
    // -------------------

    // 4. Проверяем, изменился ли СТАТУС, СУММА, КОЛИЧЕСТВО или ДАТА
    $isDataChanged = (
        $newStatus != $currentStatus || 
        $totalCount !== $currentCount || 
        $totalSum !== $currentSumm || 
        $maxDealDate !== $currentDate
    );
    
    // Если хоть что-то поменялось (или если контакт новый/пустой) — добавляем в BATCH на обновление
    if ($newStatus !== '' && $isDataChanged) {
        $batchUpdateCmd['update_' . $contactId] = 'crm.contact.update?' . http_build_query([
            'id' => $contactId,
            'fields' => [
                $statusField => (string)$newStatus, // Приведение к строке кастомного поля!
                $countField  => $totalCount,
                $summField   => $totalSum,
                $dateField   => (string)$maxDealDate
            ]
        ]);
    }
    $processedCount++;

    // Небольшая пауза, чтобы не словить ошибку 503 (Limit Exceeded) от Битрикса
    // usleep(200000); 
}

// 5. Отправляем BATCH-запрос на обновление (Запрос 3 - обновляем пачку контактов разом)
if (!empty($batchUpdateCmd)) {
    callBitrix24('batch', ['halt' => 0, 'cmd' => $batchUpdateCmd], $webhookUrl);
}

// Сохраняем прогресс для следующего запуска
file_put_contents($stateFile, $highestId);

echo "Обработано контактов: {$processedCount}. Последний ID: {$highestId}";
?>