<?php

include('vars.php');

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-03-14T19:00:00-05:00&count=100';

$result = mc_curl($url);

$us_unsub = 0;
$eu_unsub = 0;

foreach ($result['campaigns'] as $campaign) {
  $rep_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/reports/'.$campaign['id'];
  $send = mc_curl($rep_url);
  
  // echo $send['unsubscribed'];
  // echo '<br>';

    if (strpos($send['campaign_title'], 'Europe') !== false) {
      $eu_unsub += $send['unsubscribed'];
    } else {
      $us_unsub += $send['unsubscribed'];
    }
}

$mcu_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/lists/' . MC_LIST_ID . '/interest-categories/' . MC_SUBS_ID . '/interests/' . MC_MCU_GROUP_ID;

$eu_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/lists/' . MC_LIST_ID . '/interest-categories/' . MC_SUBS_ID . '/interests/' . MC_EU_GROUP_ID;

$mcu_subs = mc_curl($mcu_url);
$eu_subs = mc_curl($eu_url);

$mcu_percent = ($us_unsub/$mcu_subs['subscriber_count'])*100;
$eu_percent = ($eu_unsub/$eu_subs['subscriber_count'])*100;


echo 'US Unsubs: ' . $us_unsub;
echo '<br>';
echo 'List Size:' . number_format($mcu_subs['subscriber_count']);
echo '<br>';
echo 'Percent of List: ' . number_format((float)$mcu_percent, 2, '.', '') . '%';
echo '<br>';
echo 'Percent of Unsubs: ' . number_format((float)(($us_unsub/($us_unsub+$eu_unsub))*100), 2, '.', '') . '%';
echo '<br>';
echo '<br>';
echo 'EU Unsubs: ' . $eu_unsub;
echo '<br>';
echo 'List Size:' . number_format($eu_subs['subscriber_count']);
echo '<br>';
echo 'Percent of List: ' . number_format((float)$eu_percent, 2, '.', '') . '%';
echo '<br>';
echo 'Percent of Unsubs: ' . number_format((float)(($eu_unsub/($us_unsub+$eu_unsub))*100), 2, '.', '') . '%';


function mc_curl($url) {
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_USERPWD, 'user:' . MC_API_KEY);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

  $result = curl_exec($ch);
  // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  curl_close($ch);

  return json_decode($result, true);
}

function dump_pre($content) {
  echo '<pre>';
  var_dump($content);
  echo '</pre>';
}