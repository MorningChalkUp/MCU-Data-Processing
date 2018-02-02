<?php
  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once '../../inc/vars.php';
  require_once '../../inc/cm/csrest_campaigns.php';
  require_once '../../inc/cm/csrest_clients.php';

  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Clients(
      CM_CLIENT_ID, 
      $auth);

  $result = $wrap->get_campaigns();

  $domains = array();

  // Campagn Monitor
  foreach ($result->response as $campaign) {
    if (strtotime($campaign->SentDate) < strtotime('2018/01/01')) {

      $send = new CS_REST_Campaigns($campaign->CampaignID, $auth);
      
      $page = 1;
      $size = 1000;
      $break = false;
      $clicks = [];
      do {
        $click_json = $send->get_clicks('2000-01-01', $page, $size);
        $clicks = array_merge($clicks, $click_json->response->Results);
        if (count($click_json->response->Results) < $size) {
          $break = true;
        }
        ++$page;
      } while (!$break);

      foreach($clicks as $click) {
        $host = parse_url($click->URL, PHP_URL_HOST);
        if (isset($domains[$host])) {
          ++$domains[$host];
        } else {
          $domains[$host] = 1;
        }
      }
    }
  }


  // MailChimp
  $break = false;
  $count=100;
  $offset=0;
  do {
    $url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?count='.$count.'&offset='.$offset;

    $result = mc_curl($url);

    if ($result['total_items'] < $count) {
      $break = true;
    }

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
  } while (!$break);

  $url = 'https://' . MC_DATA_CENTER . '.api.mailchimp.com/3.0/campaigns?count=100';

  

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
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MCU Links</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

  </head>
  <body>
    <div class="row">
      <di class="col">
        <h1 class="text-center">Link Report</h1>
      </di>
    </div>

    <div class="container">
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
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  </body>
</html>