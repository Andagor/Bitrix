<?php

function get($param, $next = 0)
{
    $appParams = http_build_query(array('halt' => 0, 'cmd' => $param));
    $appRequestUrl = '';
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

function getUserName($userID)
{
    $param['act'] = "user.get?".http_build_query(array("ID" => $userID));
    $Result = get($param);
    $FIO = $Result['result']['result']['act']['0']['LAST_NAME'].' '.$Result['result']['result']['act']['0']['NAME'];
    return $FIO;
}

function getDeal($dealID)
{
    $param['act'] = "crm.deal.get?".http_build_query(array('id' => $dealID));
    $Result = get($param);
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
