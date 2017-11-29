<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../inc/vars.php';
require_once '../inc/cm/csrest_lists.php';
require_once '../inc/cm/csrest_subscribers.php';
require_once '../inc/db/class.DBPDO.php';

try {
  $con = new DBPDO();
} catch (Exception $e) {
  echo 'There was an issue establishing a connection with the Database. Please contact <a href="mailto:eric@morningchalkup.com">eric@morningchalkup.com</a> for assistance.';
}

$auth = array('api_key' => CM_API_KEY);
$list = new CS_REST_Lists(CM_MCU_LIST_ID, $auth);
$subs = new CS_REST_Subscribers(CM_MCU_LIST_ID, $auth);

// $pageSize = 100;
// $pages = ceil(getListSize($list) / $pageSize);

// $start = 24275/$pageSize;

/*for($i = 1; $i <= $pages; ++$i) {
  $result = $list->get_active_subscribers('', $i, $pageSize, 'date', 'asc');
  $active = json_decode(json_encode($result, true));
  if (!inDB($sub->EmailAddress, $con)){
    foreach ($active->response->Results as $sub) {
      addEmail($sub->EmailAddress, $con);
      echo $sub->EmailAddress . "\n";
    }
  }
}*/

$data = getDB($con);

foreach ($data as $sub) {
  addToDB($sub['email'], $subs, $con);
  echo $sub['email'] . "\n";
}

echo "Done!";

function getListSize($wrap) {
  $result = $wrap->get_stats();

  return $result->response->TotalActiveSubscribers;
}

function getDB($con) {
  return $con->fetchAll("SELECT email FROM cu_people ORDER BY pid ASC");
}

function addEmail($email, $con) {
  $data = array('email' => $email);

  $query = "INSERT INTO cu_people(email) VALUES(:email)";

  $r = $con->execute($query, $data);
}

function addToDB($email, $subs, $con) {

  $result = $subs->get($email);

  $data = mapCMtoDB($result->response);
  foreach ($data as $key => $value) {
    if ($key != 'email') {
      $cols[] = $key . " = ?";
      $vals[] = $value;
    }
  }

  $vals[] = $email;

  $query = "UPDATE cu_people SET " . implode(' ',$cols) . " WHERE email = ?";

  $r = $con->execute($query, $vals);

}

function mapCMtoDB($data) {
  // $dbData['email'] = $data->EmailAddress;
  $date = date_create_from_format('Y-m-d H:i:s', $data->Date);
  $dbData['date_added'] = date_format($date, 'Y-m-d H:i:s');
  if (isset($data->Name)) {
    $name = explode(' ',$data->Name);
    if (!isset($name[1])) {
      $name[1] = '';
    }
    $dbData['fname'] = $name[0];
    $dbData['lname'] = $name[1];
  } else {
    $dbData['fname'] = '';
    $dbData['lname'] = '';
  }
  foreach ($data->CustomFields as $field) {
    switch ($field->Key) {
      case 'About':
        $dbData['about'] = $field->Value;
        break;
      case 'Affiliate':
        $dbData['affiliate'] = $field->Value;
        break;
      case 'US Edition':
        $dbData['us_edition'] = $field->Value;
        break;
      case 'EU Edition':
        $dbData['eu_edition'] = $field->Value;
        break;
      case 'Unengaged':
        $dbData['unengaged'] = $field->Value;
        break;
      case 'Zip Code':
        $dbData['zip'] = $field->Value;
        break;
      case 'Years Doing Crossfit':
        $dbData['years_of_crossfit'] = $field->Value;
        break;
      case 'Story Interests':
        $dbData['story_interests'] = $field->Value;
        break;
      case 'Has Affiliate':
        $dbData['has_affiliate'] = $field->Value;
        break;
      case 'Be An Ambassador':
        $dbData['be_ambassador'] = $field->Value;
        break;
      case 'Address':
        $dbData['address1'] = $field->Value;
        break;
      case 'Games Level Athlete':
        $dbData['games_lvl'] = $field->Value;
        break;
      case 'City':
        $dbData['city'] = $field->Value;
        break;
      case 'State':
        $dbData['state'] = $field->Value;
        break;
      case 'Country':
        $dbData['country'] = $field->Value;
        break;
      case 'Birthday':
        $dbData['birthday'] = $field->Value;
        break;
      case 'Alert Edition':
        $dbData['alert_edition'] = $field->Value;
        break;
    }
  }

  if (isset($dbData['zip'])) {
    $location = getLocation($dbData['zip']);
    if ($location) {
      $dbData['city'] = isset($location['city']) ? $location['city'] : '';
      $dbData['state'] = isset($location['state']) ? $location['state'] : '';
      $dbData['country'] = isset($location['country']) ? $location['country'] : '';
    }
  }
  return $dbData;
}

function getLocation($zip) {
  $key = 'AIzaSyBwTd5GESfcwrhWMp1oaIcWqeKkERZDrxc';
  $url = 'https://maps.googleapis.com/maps/api/geocode/json';

  $call = $url . '?address=' . $zip . '&key=' . $key;

  $result = file_get_contents($call);
  $data = json_decode($result, true);

  if (isset($data['results'][0])) {
    foreach ($data['results'][0]['address_components'] as $key) {
      switch ($key['types'][0]) {
        case 'locality':
          $loc['city'] = $key['long_name'];
          break;
        case 'administrative_area_level_1':
          $loc['state'] = $key['long_name'];
          break;
        case 'country':
          $loc['country'] = $key['long_name'];
          break;
      }
    }
    return $loc;
  }
  return false;
}

function inDB($email, $con) {
  $result = $con->fetch("SELECT email FROM cu_people WHERE email = ?", $email);
  if ($result) {
    return true;
  }
  return false;
}