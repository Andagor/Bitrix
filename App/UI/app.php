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

$table_pay_form = '<table class="table">';
    $table_pay_form .= '<thead>
                <tr>
                    <th class="column-header-text" > Форма оплаты </th>
                    <th class="column-header"      > Приход		  </th>
                    <th class="column-header"      > Расход		  </th>
                    <th class="column-header"      > Итого		  </th>
                </tr>
            </thead>';
    $table_pay_form .= '<tbody>';
        foreach ($ar_Result_Pay_Form as $key => $row)
        {
            $title = ($row['pay_form']) ? $row['pay_form'] : 'Не выбрано';
        	$table_pay_form .= '<tr>';
        		$table_pay_form .= '<td class="column-text">'. $title	           .'</td>';
        		$table_pay_form .= '<td>'					 . $row['summ_income'] .'</td>';
        		$table_pay_form .= '<td>'					 . $row['summ_output'] .'</td>';
        		$table_pay_form .= '<td class="column-itog">'. $row['summ_result'] .'</td>';
        	$table_pay_form .= '</tr>';

        	$summ_input  += $row['summ_income'];
        	$summ_output += $row['summ_output'];
        }
        $delta = $summ_input - $summ_output;
        $table_pay_form .= '<tr class="t-foot">
                    <td class="t-foot-text"> Всего: 		  </td>
                    <td>'.                   $summ_input 	.'</td>
                    <td>'.                   $summ_output   .'</td>
                    <td>'.                   $delta 		.'</td>
                </tr>';
    $table_pay_form .= '</tbody>';
$table_pay_form .= '</table>';



// Формирование таблицы по отделам
$summ_input  = 0;
$summ_output = 0;

$table_department = '<table class="table">';
    $table_department .= '<thead>
                <tr>
                    <th class="column-header-text" > Отдел   </th>
                    <th class="column-header"      > Приход  </th>
                    <th class="column-header"      > Расход  </th>
                    <th class="column-header"      > Итого   </th>
                </tr>
            </thead>';
    $table_department .= '<tbody>';
        foreach ($ar_Result_Department as $key => $row)
        {
            $title = ($row['department']) ? $row['department'] : 'Не выбрано';
            $table_department .= '<tr>';
                $table_department .= '<td class="column-text">'. $title              .'</td>';
                $table_department .= '<td>'                    . $row['summ_income'] .'</td>';
                $table_department .= '<td>'                    . $row['summ_output'] .'</td>';
                $table_department .= '<td class="column-itog">'. $row['summ_result'] .'</td>';
            $table_department .= '</tr>';

            $summ_input  += $row['summ_income'];
            $summ_output += $row['summ_output'];
        }
        $delta = $summ_input - $summ_output;
        $table_department .= '<tr class="t-foot">
                    <td class="t-foot-text"> Всего:         </td>
                    <td>'.                   $summ_input  .'</td>
                    <td>'.                   $summ_output .'</td>
                    <td>'.                   $delta       .'</td>
                </tr>';
    $table_department .= '</tbody>';
$table_department .= '</table>';



// Формирование таблицы по статьям
$summ_input  = 0;
$summ_output = 0;

$table_article = '<table class="table">';
    $table_article .= '<thead>
                <tr>
                    <th class="column-header-text" > Статья </th>
                    <th class="column-header"      > Приход        </th>
                    <th class="column-header"      > Расход        </th>
                    <th class="column-header"      > Итого         </th>
                </tr>
            </thead>';
    $table_article .= '<tbody>';
        foreach ($ar_Result_Article as $key => $row)
        {
            $title = ($row['article']) ? $row['article'] : 'Не выбрано';
            $table_article .= '<tr>';
                $table_article .= '<td class="column-text">'. $title              .'</td>';
                $table_article .= '<td>'                    . $row['summ_income'] .'</td>';
                $table_article .= '<td>'                    . $row['summ_output'] .'</td>';
                $table_article .= '<td class="column-itog">'. $row['summ_result'] .'</td>';
            $table_article .= '</tr>';

            $summ_input  += $row['summ_income'];
            $summ_output += $row['summ_output'];
        }
        $delta = $summ_input - $summ_output;
        $table_article .= '<tr class="t-foot">
                    <td class="t-foot-text"> Всего:         </td>
                    <td>'.                   $summ_input  .'</td>
                    <td>'.                   $summ_output .'</td>
                    <td>'.                   $delta       .'</td>
                </tr>';
    $table_article .= '</tbody>';
$table_article .= '</table>';

?>