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

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MCU Emails</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col">
          <table class="table">
            <thead>
              <th role="col">ID</th>
              <th role="col">Name</th>
              <!-- <th role="col">Sent</th>
              <th role="col">Open Rate</th> -->
            </thead>
            <tbody>
              <?php foreach ($result->response as $campaign): ?>
                <?php 
                  /*$camp_wrap = new CS_REST_Campaigns($campaign->CampaignID, $auth);
                  $campaign = $camp_wrap->get_summary();
                  $rate = $campaign->response->UniqueOpened / $campaign->response->Recipients * 100;*/
                ?>
                <tr>
                  <th role="row"><?php echo $campaign->CampaignID ?></th>
                  <td><a href="details.php?id=<?php echo $campaign->CampaignID ?>"><?php echo $campaign->Name ?></a></td>
                <!--   <td><?php echo $campaign->response->Recipients ?></td>
                  <td><?php echo $rate; ?>%</td> -->
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
  </body>
</html>