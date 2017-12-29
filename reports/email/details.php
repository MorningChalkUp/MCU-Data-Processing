<?php
  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  } else {
    header('Location: ./');
  }

  require_once '../../inc/vars.php';
  require_once '../../inc/cm/csrest_campaigns.php';
  require_once '../../inc/cm/csrest_clients.php';

  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Campaigns($id, $auth);

  $result = $wrap->get_summary();

  // $click_json = $wrap->get_clicks('2000-01-01', 1, 1000);

  $page = 1;
  $size = 1000;
  $break = false;
  $clicks = [];
  do {
    $click_json = $wrap->get_clicks('2000-01-01', $page, $size);
    $clicks = array_merge($clicks, $click_json->response->Results);
    if (count($click_json->response->Results) < $size) {
      $break = true;
    }
    ++$page;
  } while (!$break);

  $ad_domains = array('www.flapjacked.com', 'www.amazon.com');
  $ad_click = 0;

  foreach ($clicks as $click) {
    foreach ($ad_domains as $ad_domain) {
      $url = parse_url($click->URL);
      if ($url['host'] == $ad_domain) {
        ++$ad_click;
        if (isset($links[$click->URL])) {
          ++$links[$click->URL];
        } else {
          $links[$click->URL] = 1;
        }
      }
    }
  }

  $wrap2 = new CS_REST_Clients(
      CM_CLIENT_ID, 
      $auth);

  $campaign_list = $wrap2->get_campaigns();

  foreach ($campaign_list->response as $campaign) {
    if ($campaign->CampaignID == $id) {
      $title = $campaign->Name;
      break;
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?> | MCU Emails</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

  </head>
  <body>
    <div class="row">
      <div class="col">
        <a href="./"><- Back</a>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col">
          <h2><a href="<?php echo $result->response->WebVersionURL ?>" target="_blank"><?php echo $title; ?></a><br><small>Campaign ID: <?php echo $id; ?></small></h2>
          
        </div>
      </div>
      <div class="row">
        <div class="col">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Recipiants</th>
                <th scope="col">Unique Opens</th>
                <th scope="col">Clicks</th>
                <th scope="col">Total Clicks</th>
                <th scope="col">Ad Clicks</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $result->response->Recipients ?></td>
                <td><?php echo $result->response->UniqueOpened ?></td>
                <td><?php echo $result->response->Clicks ?></td>
                <td><?php echo count($clicks);  ?></td>
                <td><?php echo $ad_click;  ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <?php foreach ($links as $link => $count): ?>
            <p><strong><?php echo $count ?></strong> - <a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  </body>
</html>