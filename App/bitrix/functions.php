<?php

require_once '/app_config.php';

$url = BITRIXWEBHOOK;

function getUserName($userID)
{
    $param['act'] = "user.get?".http_build_query(array("ID" => $userID));
    $Result = get($param, $url);
    $FIO = $Result['result']['result']['act']['0']['LAST_NAME'].' '.$Result['result']['result']['act']['0']['NAME'];
    return $FIO;
}

function getDeal($dealID)
{
    $param['act'] = "crm.deal.get?".http_build_query(array('id' => $dealID));
    $Result = get($param, $url);
    $DealName = $Result['result']['result']['act']['TITLE'];
    $Deal = '<a href="'.$dealID.'/" target="_blank">'.$DealName.'</a>';
    return $Deal;
}

function compare_date($targetDate, $dateFrom, $dateTo)
{
    $target_Date = strtotime($targetDate);
    $date_From = strtotime($dateFrom);
    $date_To = strtotime($dateTo);
    if ($target_Date >= $date_From && $target_Date < $date_To)
    {
        return 1;
    }
    return 0;
}

function AssembledArray($value, &$summ_input, &$summ_output, &$itogo)
{
    if ($value->direction == 1)
    {
        $summ_input += $value->summ;
    }
    if ($value->direction == 0)
    {
        $summ_output += $value->summ;
    }
        $itogo = $summ_input - $summ_output;
    return 0;
}

function sumByColumn($rgData, $sColumn)
{
   $rgIndex = array();
   $rgResult = array();
   array_walk($rgData, function($rgValue, $iIndex) use (&$rgIndex, $sColumn)
   {
      $rgIndex[$rgValue[$sColumn]][] = $iIndex;
   });
   array_walk($rgIndex, function($rgValue) use (&$rgData, &$rgResult, $sColumn)
   {
      $rgTemp = array();
      foreach($rgValue as $iIndex)
      {
         foreach($rgData[$iIndex] as $sKey => $mValue)
         {
            $rgTemp[$sKey] = ($sColumn != $sKey && is_numeric($mValue)) ? $rgTemp[$sKey] + $mValue : $mValue;
         }
      };
      $rgResult[] = $rgTemp;
   });
   return $rgResult;
}

?>
