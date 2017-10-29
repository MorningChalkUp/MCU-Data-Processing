<?php

include('vars.php');

$limit = 100;
$total = 245;

$eu_clicks = 0;
$eu_sent = 0;
$us_clicks = 0;
$us_sent = 0;
$clicks = 0;
$sent = 0;

echo 'Total Clicks: ' . $clicks;
/*echo "<br>";
echo 'Total Sent: ' . $sent;
echo "<br>";
echo "<br>";
echo 'US Clicks: ' . $us_clicks;
echo "<br>";
echo 'US Sent: ' . $us_sent;
echo "<br>";
echo "<br>";
echo 'EU Clicks: ' . $eu_clicks;
echo "<br>";
echo 'EU Sent: ' . $eu_sent;*/
echo "<br>";
echo "<br>";
echo "<br>";

ob_flush();
flush();

$url1 = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit;
$url2 = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit;
$url3 = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit * 2;
$url4 = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit * 3;
$url5 = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit * 4;
$url6 = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit * 5;

$result1 = mc_curl($url1);
$result2 = mc_curl($url2);
$result3 = mc_curl($url3);
$result4 = mc_curl($url4);
$result5 = mc_curl($url5);
$result6 = mc_curl($url6);

foreach ($result1['campaigns'] as $email) {
  $clicks += $email['report_summary']['clicks'];
  ++$sent;
}
foreach ($result2['campaigns'] as $email) {
  $clicks += $email['report_summary']['clicks'];
  ++$sent;
}
foreach ($result3['campaigns'] as $email) {
  $clicks += $email['report_summary']['clicks'];
  ++$sent;
}
foreach ($result4['campaigns'] as $email) {
  $clicks += $email['report_summary']['clicks'];
  ++$sent;
}
foreach ($result5['campaigns'] as $email) {
  $clicks += $email['report_summary']['clicks'];
  ++$sent;
}
foreach ($result6['campaigns'] as $email) {
  $clicks += $email['report_summary']['clicks'];
  ++$sent;
}



// $url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-01-01T00:00:00-00:00&sort_field=send_time&sort_dir=ASC&count=' . $limit;

// $result = mc_curl($url);

  
// foreach ($result['campaigns'] as $email) {
//   /*$rep_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/reports/'.$email['id'];
//   $send = mc_curl($rep_url);*/

//   $clicks += $email['report_summary']['subscriber_clicks'];
//   ++$sent;

//   /*$test = explode('Morning Chalk Up', $send['campaign_title']);

//   if (strpos($send['campaign_title'], 'Europe') !== false) {
//       ++$eu_sent;
//       $eu_clicks += $email['report_summary']['subscriber_clicks'];
//     } else if ($test[1] == '') {
//       ++$us_sent;
//       $us_clicks += $email['report_summary']['subscriber_clicks'];
//     }
// */
// }

// echo 'Total Clicks: ' . $clicks;
// /*echo "<br>";
// echo 'Total Sent: ' . $sent;
// echo "<br>";
// echo "<br>";
// echo 'US Clicks: ' . $us_clicks;
// echo "<br>";
// echo 'US Sent: ' . $us_sent;
// echo "<br>";
// echo "<br>";
// echo 'EU Clicks: ' . $eu_clicks;
// echo "<br>";
// echo 'EU Sent: ' . $eu_sent;*/
// echo "<br>";
// echo "<br>";
// echo "<br>";

// ob_flush();
// flush();

// $url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-00-01T00:00:00-00:00&sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit;

// $result = mc_curl($url);

// foreach ($result['campaigns'] as $email) {

//   /*$rep_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/reports/'.$campaign['id'];
//   $send = mc_curl($rep_url);*/

//   $clicks += $email['report_summary']['subscriber_clicks'];
//   ++$sent;

//   $test = explode('Morning Chalk Up', $send['campaign_title']);

//   if (strpos($send['campaign_title'], 'Europe') !== false) {
//       ++$eu_sent;
//       $eu_clicks += $email['report_summary']['subscriber_clicks'];
//     } else if ($test[1] == '') {
//       ++$us_sent;
//       $us_clicks += $email['report_summary']['subscriber_clicks'];
//     }
// }

// echo 'Total Clicks: ' . $clicks;
// /*echo "<br>";
// echo 'Total Sent: ' . $sent;
// echo "<br>";
// echo "<br>";
// echo 'US Clicks: ' . $us_clicks;
// echo "<br>";
// echo 'US Sent: ' . $us_sent;
// echo "<br>";
// echo "<br>";
// echo 'EU Clicks: ' . $eu_clicks;
// echo "<br>";
// echo 'EU Sent: ' . $eu_sent;*/
// echo "<br>";
// echo "<br>";
// echo "<br>";

// ob_flush();
// flush();

// $url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?since_send_time=2017-00-01T00:00:00-00:00&sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit * 2;

// $result = mc_curl($url);

// foreach ($result['campaigns'] as $email) {

//   /*$rep_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/reports/'.$campaign['id'];
//   $send = mc_curl($rep_url);*/

//   $clicks += $email['report_summary']['subscriber_clicks'];
//   ++$sent;

//   $test = explode('Morning Chalk Up', $send['campaign_title']);

//   /*if (strpos($send['campaign_title'], 'Europe') !== false) {
//       ++$eu_sent;
//       $eu_clicks += $email['report_summary']['subscriber_clicks'];
//     } else if ($test[1] == '') {
//       ++$us_sent;
//       $us_clicks += $email['report_summary']['subscriber_clicks'];
//     }*/

// }

echo 'Total Clicks: ' . $clicks;
/*echo "<br>";
echo 'Total Sent: ' . $sent;
echo "<br>";
echo "<br>";
echo 'US Clicks: ' . $us_clicks;
echo "<br>";
echo 'US Sent: ' . $us_sent;
echo "<br>";
echo "<br>";
echo 'EU Clicks: ' . $eu_clicks;
echo "<br>";
echo 'EU Sent: ' . $eu_sent;*/
echo "<br>";
echo "<br>";
echo "<br>";

ob_flush();
flush();


ob_end_flush();

// $url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?sort_field=send_time&sort_dir=ASC&count=' . $limit . '&offset=' . $limit * 2;

echo "Done";


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