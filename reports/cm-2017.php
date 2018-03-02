<?php
  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once '../inc/vars.php';
  require_once '../inc/cm/csrest_campaigns.php';
  require_once '../inc/cm/csrest_clients.php';

  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Clients(
      CM_CLIENT_ID, 
      $auth);

  $results = $wrap->get_campaigns();
  $format = 'Y-m-d H:i:s';
  foreach ($results->response as $result) {
    $date = DateTime::createFromFormat($format, $result->SentDate);
    if ($date->format('Y') < '2018') {
      $wrap2 = new CS_REST_Campaigns($result->CampaignID, $auth);
      $summery = $wrap2->get_summary();


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
      echo $result->Name;
      echo ',';
      echo $summery->response->Recipients;
      echo ',';
      echo $summery->response->TotalOpened;
      echo ',';
      echo $summery->response->UniqueOpened;
      echo ',';
      echo $summery->response->Clicks;

      echo "<br>";
    }
  }

?>