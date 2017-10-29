<?php

include('vars.php');

$format = 'Y-m-d\TH:i:sP';

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2016-01-01T00:00:00-08:00&before_send_time=2017-01-01T00:00:00-08:00&sort_field=send_time&sort_dir=ASC&count=500';

$result = mc_curl($url);

// dump_pre($result);
echo 'Day Of Week,Year,Month,Day,Date,Title,Sent,Opened,Unique Opened,Clicks,Sub Clicks';
echo '<br>';

foreach ($result['campaigns'] as $email) {
  $date = DateTime::createFromFormat($format, $email['send_time']);
  echo $date->format('w');
  echo ',';
  echo $date->format('Y');
  echo ',';
  echo $date->format('m');
  echo ',';
  echo $date->format('d');
  echo ',';
  echo $date->format('m/d/Y');
  echo ',';
  echo $email['settings']['title'];
  echo ',';
  echo $email['recipients']['recipient_count'];
  echo ',';
  echo $email['report_summary']['opens'];
  echo ',';
  echo $email['report_summary']['unique_opens'];
  echo ',';
  echo $email['report_summary']['clicks'];
  echo ',';
  echo $email['report_summary']['subscriber_clicks'];
  echo '<br>';
}

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-01-01T00:00:00-08:00&before_send_time=2017-04-01T00:00:00-08:00&sort_field=send_time&sort_dir=ASC&count=200';

$result = mc_curl($url);

foreach ($result['campaigns'] as $email) {
  $date = DateTime::createFromFormat($format, $email['send_time']);
  echo $date->format('w');
  echo ',';
  echo $date->format('Y');
  echo ',';
  echo $date->format('m');
  echo ',';
  echo $date->format('d');
  echo ',';
  echo $date->format('m/d/Y');
  echo ',';
  echo $email['settings']['title'];
  echo ',';
  echo $email['recipients']['recipient_count'];
  echo ',';
  echo $email['report_summary']['opens'];
  echo ',';
  echo $email['report_summary']['unique_opens'];
  echo ',';
  echo $email['report_summary']['clicks'];
  echo ',';
  echo $email['report_summary']['subscriber_clicks'];
  echo '<br>';
}

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-04-01T00:00:00-08:00&before_send_time=2017-07-01T00:00:00-08:00&sort_field=send_time&sort_dir=ASC&count=200';

$result = mc_curl($url);

foreach ($result['campaigns'] as $email) {
  $date = DateTime::createFromFormat($format, $email['send_time']);
  echo $date->format('w');
  echo ',';
  echo $date->format('Y');
  echo ',';
  echo $date->format('m');
  echo ',';
  echo $date->format('d');
  echo ',';
  echo $date->format('m/d/Y');
  echo ',';
  echo $email['settings']['title'];
  echo ',';
  echo $email['recipients']['recipient_count'];
  echo ',';
  echo $email['report_summary']['opens'];
  echo ',';
  echo $email['report_summary']['unique_opens'];
  echo ',';
  echo $email['report_summary']['clicks'];
  echo ',';
  echo $email['report_summary']['subscriber_clicks'];
  echo '<br>';
}

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-07-01T00:00:00-08:00&before_send_time=2017-10-01T00:00:00-08:00&sort_field=send_time&sort_dir=ASC&count=200';

$result = mc_curl($url);

foreach ($result['campaigns'] as $email) {
  $date = DateTime::createFromFormat($format, $email['send_time']);
  echo $date->format('w');
  echo ',';
  echo $date->format('Y');
  echo ',';
  echo $date->format('m');
  echo ',';
  echo $date->format('d');
  echo ',';
  echo $date->format('m/d/Y');
  echo ',';
  echo $email['settings']['title'];
  echo ',';
  echo $email['recipients']['recipient_count'];
  echo ',';
  echo $email['report_summary']['opens'];
  echo ',';
  echo $email['report_summary']['unique_opens'];
  echo ',';
  echo $email['report_summary']['clicks'];
  echo ',';
  echo $email['report_summary']['subscriber_clicks'];
  echo '<br>';
}

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-10-01T00:00:00-08:00&sort_field=send_time&sort_dir=ASC&count=200';

$result = mc_curl($url);

foreach ($result['campaigns'] as $email) {
  $date = DateTime::createFromFormat($format, $email['send_time']);
  echo $date->format('w');
  echo ',';
  echo $date->format('Y');
  echo ',';
  echo $date->format('m');
  echo ',';
  echo $date->format('d');
  echo ',';
  echo $date->format('m/d/Y');
  echo ',';
  echo $email['settings']['title'];
  echo ',';
  echo $email['recipients']['recipient_count'];
  echo ',';
  echo $email['report_summary']['opens'];
  echo ',';
  echo $email['report_summary']['unique_opens'];
  echo ',';
  echo $email['report_summary']['clicks'];
  echo ',';
  echo $email['report_summary']['subscriber_clicks'];
  echo '<br>';
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

function dump_pre($content) {
  echo '<pre>';
  var_dump($content);
  echo '</pre>';
}