<?php

include('vars.php');

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?count=6';

$result = mc_curl($url);

foreach ($result['campaigns'] as $campaign) {
  $rep_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/reports/'.$campaign['id'].'/click-details?count=50';
  $links = mc_curl($rep_url);
  foreach ($links['urls_clicked'] as $link) {
    print $link['url'] . ' > ' . $link['total_clicks'] . '<br>';
  }
}

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