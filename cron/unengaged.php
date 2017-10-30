<?php

require_once '../process/vars.php';
require_once '../process/cm/csrest_lists.php';
require_once '../process/cm/csrest_subscribers.php';

$auth = array('api_key' => CM_API_KEY);
$list = new CS_REST_Lists(CM_MCU_LIST_ID, $auth);

$pageSize = 100;
$pages = ceil(getListSize() / $pageSize);

$new = getNewArray();

for($i = 1; $i <= $pages; ++$i) {
  $result = $list->get_active_subscribers('', $i, $pageSize, 'email', 'asc');
  $active = json_decode(json_encode($result, true));
  foreach ($active->response->Results as $sub) {
    if (!in_array($sub->EmailAddress, $new)) {
      $unengaged = isUnengaged($sub->EmailAddress);
      updateEngagement($sub->EmailAddress, $unengaged);
      echo $sub->EmailAddress . " - " . $unengaged . "/n";
    }
  }
}

echo "Done!";

function getNewArray() {
  $auth = array('api_key' => CM_API_KEY);
  $list = new CS_REST_Lists(CM_MCU_LIST_ID, $auth);

  $pageSize = 1000;
  $pages = ceil(getNewSize() / $pageSize);

  for($i = 1; $i <= $pages; ++$i) {
    $result = $list->get_active_subscribers(date('Y-m-d', strtotime('-7 days')), $i, $pageSize);
    $active = json_decode(json_encode($result, true));
    foreach ($active->response->Results as $sub) {
      $new[] = $sub->EmailAddress;
    }
  }

  return $new;
}

function isUnengaged($email) {
  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Subscribers(CM_MCU_LIST_ID, $auth);
  $result = $wrap->get_history($email);

  $comp_date = date('Y-m-d', strtotime('-14 days'));

  foreach ($result->response as $email) {
    if (count($email->Actions) != 0) {
      foreach ($email->Actions as $action) {
        if ($action->Event == 'Open') {
          $date = DateTime::createFromFormat('Y-m-d H:i:s', $action->Date);
          if ($date > $comp_date) {
            return 0;
          }
        }
      }
    }
  }
  return 1;
}

function getListSize() {
  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Lists(CM_MCU_LIST_ID, $auth);
  $result = $wrap->get_stats();

  return $result->response->TotalActiveSubscribers;
}

function getNewSize() {
  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Lists(CM_MCU_LIST_ID, $auth);
  $result = $wrap->get_stats();

  return $result->response->NewActiveSubscribersThisWeek;
}

function updateEngagement($email, $unengaged) {
  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Subscribers(CM_MCU_LIST_ID, $auth);

  $result = $wrap->update($email, array(
    'CustomFields' => array(
      array(
        'Key' => 'Unengaged',
        'Value' => (int)$unengaged
      )
    ),
  ));
}