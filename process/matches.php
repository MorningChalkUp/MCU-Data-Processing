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

$m1 = $con->fetchAll('SELECT * FROM m1');
$m2 = $con->fetchAll('SELECT * FROM m2');
$combined = array();

foreach ($m1 as $user) {
  $added = false;
  foreach ($m2 as $match) {
    if ($user['email'] == $match['email']) {
      $tmp = array();
      foreach ($match as $key => $value) {
        if ($value != '' || $value != null) {
          $tmp[$key] = $value;
        } else {
          $tmp[$key] = $user[$key];
        }
      }
      $added = true;
      addToDB($tmp);
      // $combined[] = $tmp;
      break;
    }
  }
  if (!$added) {
    addToDB($user);
    // $combined[] = $user;
  }
}

foreach ($m2 as $user) {
  $found = false;
  foreach ($m1 as $match) {
    if ($user['email'] == $match['email']) {
      $found = true;
      break;
    }
  }
  if (!$found) {
    addToDB($user);
    // $combined[] = $user;
  }
}

echo "Done";


function addToDB($data) {

  global $con;

  foreach ($data as $key => $value) {
    $cols[] = $key;
    $vals[] = ':' . $key;
  }

  $query = "INSERT INTO mc(" . implode(', ', $cols) . ") VALUES(" . implode(', ', $vals) . ")";

  $r = $con->execute($query, $data);

}