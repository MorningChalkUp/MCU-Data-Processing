<?php

include('vars.php');

$url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?count=100';

$result = mc_curl($url);
$domains = array();
foreach ($result['campaigns'] as $campaign) {
  $rep_url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/reports/'.$campaign['id'].'/click-details?count=100';
  $links = mc_curl($rep_url);
  
  foreach ($links['urls_clicked'] as $link) {
    $host = parse_url($link['url'], PHP_URL_HOST);
    if (isset($domains[$host])) {
      $domains[$host] += $link['total_clicks'];
    } else {
      $domains[$host] = $link['total_clicks'];
    }
  }
}
?>
 <?php foreach($domains as $domain => $count): ?>
  <div class="row">
    <div class="col">
      <table class="table">
        <tbody>
          <tr>
            <td><?php echo $domain; ?></td>
            <td><?php echo $count; ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
<?php endforeach; ?>
<?php
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