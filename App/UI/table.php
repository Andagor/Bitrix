<?php

require_once 'functions.php';

$ar_Result_Data = array_merge($ar_Data_Input, $ar_Data_Output);
/*echo 'Исходный массив <br>';
echo '<pre>'; print_r($ar_Result_Data); echo '</pre>';*/


// Суммирование
if ($ar_Result_Data)
{
    $ar_Result_Pay_Form   = sumByColumn($ar_Result_Data, 'pay_form');
    $ar_Result_Article    = sumByColumn($ar_Result_Data, 'article');
    $ar_Result_Department = sumByColumn($ar_Result_Data, 'department');
}
else
{
    $ar_Result_Pay_Form[] = [
        'pay_form'    => 'Нет данных',
        'summ_income' => 0,
        'summ_output' => 0,
        'summ_result' => 0
    ];

    $ar_Result_Article[] = [
        'article'     => 'Нет данных',
        'summ_income' => 0,
        'summ_output' => 0,
        'summ_result' => 0
    ];

    $ar_Result_Department[] = [
        'department'  => 'Нет данных',
        'summ_income' => 0,
        'summ_output' => 0,
        'summ_result' => 0
    ];
}
/*echo '<pre>'; print_r($ar_Result_Pay_Form); 	echo '</pre>';
echo '<pre>'; print_r($ar_Result_Article); 		echo '</pre>';
echo '<pre>'; print_r($ar_Result_Department); 	echo '</pre>';*/




// ФОРМИРОВАНИЕ ТАБЛИЦ

// Формирование таблицы по формам оплаты
$summ_input  = 0;
$summ_output = 0;

$table = '<table class="table">';
    $table .= '<thead>
                <tr>
                    <th class="column-header-text" > Форма оплаты </th>
                    <th class="column-header"      > Приход		  </th>
                    <th class="column-header"      > Расход		  </th>
                    <th class="column-header"      > Итого		  </th>
                </tr>
            </thead>';
    $table .= '<tbody>';
        foreach ($ar_Result_Pay_Form as $key => $row)
        {
            $title = ($row['pay_form']) ? $row['pay_form'] : 'Не выбрано';
        	$table .= '<tr>';
        		$table .= '<td class="column-text">'. $title	           .'</td>';
        		$table .= '<td>'					 . $row['summ_income'] .'</td>';
        		$table .= '<td>'					 . $row['summ_output'] .'</td>';
        		$table .= '<td class="column-itog">'. $row['summ_result'] .'</td>';
        	$table .= '</tr>';

        	$summ_input  += $row['summ_income'];
        	$summ_output += $row['summ_output'];
        }
        $delta = $summ_input - $summ_output;
        $table .= '<tr class="t-foot">
                    <td class="t-foot-text"> Всего: 		  </td>
                    <td>'.                   $summ_input 	.'</td>
                    <td>'.                   $summ_output   .'</td>
                    <td>'.                   $delta 		.'</td>
                </tr>';
    $table .= '</tbody>';
$table .= '</table>';
?>