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

  $clicks = 0;

  // Campagn Monitor
  foreach ($result->response as $campaign) {
    if (strtotime($campaign->SentDate) > strtotime('2018/01/01') && strtotime($campaign->SentDate) < strtotime('2018/02/01')) {

      $send = new CS_REST_Campaigns($campaign->CampaignID, $auth);
      $summery = $send->get_summary();

      $clicks += $summery->response->Clicks;

    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Total Link Clicks</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
  </head>
  <body>
    <h1 class="text-center"><?php echo number_format($clicks); ?></h1>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  </body>
</html>
