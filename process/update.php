<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../inc/vars.php';
require_once '../inc/db/class.DBPDO.php';

try {
  $con = new DBPDO();
} catch (Exception $e) {
  echo 'There was an issue establishing a connection with the Database. Please contact <a href="mailto:eric@morningchalkup.com">eric@morningchalkup.com</a> for assistance.';
}

$matches = $con->fetchAll('SELECT * FROM mc');
$shopers = $con->fetchAll('SELECT * FROM shop');

foreach ($matches as $match) {
  $cols = array(); 
  $data = array();
  foreach ($match as $key => $value) {
    if ($value != '' && $value != null && $key != 'email') {
      $cols[] = $key . ' = ?';
      $data[] = $value;
    }
  }
  if (isset($cols)) {
    $query = 'UPDATE cu_people SET ' . implode(', ', $cols) . ', country = ? WHERE email = ? ';
    $data[] = 'United States';
    $data[] = $match['email'];
/*    var_dump($query);
    echo '<br>';
    var_dump($data);
    echo '<br>';*/

    $update[] = $data;
    // $r = $con->execute($query, $data);
  }
}
echo '<pre>';
var_dump($update);
echo '</pre>';

/*
foreach ($shopers as $shoper) {
  $cols = array(); 
  $data = array();
  foreach ($shoper as $key => $value) {
    if ($value != '' && $value != null && $key != 'email') {
      $cols[] = $key . ' = ?';
      $data[] = $value;
    }
  }
  if (isset($cols)) {
    $query = 'UPDATE cu_people SET ' . implode(', ', $cols) . ', country = ? WHERE email = ? ';
    $data[] = 'United States';
    $data[] = $shoper['email'];

    $r = $con->execute($query, $data);
  }
}

echo 'done';



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
}*/