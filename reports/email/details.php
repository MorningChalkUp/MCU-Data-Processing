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

  $auth = array('api_key' => CM_API_KEY);
  $wrap = new CS_REST_Campaigns($id, $auth);

  $result = $wrap->get_summary();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title Page</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="row">
      <div class="container">
        <div class="col-sm-12">
          <h2>Recipiants: <?php echo $result->response->Recipients ?></h2>
          <h2>Total Opens: <?php echo $result->response->TotalOpened ?></h2>
          <h2>Clicks: <?php echo $result->response->Clicks ?></h2>
          <h2>Unique Opens: <?php echo $result->response->UniqueOpened ?></h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="container">
        <div class="col-sm-12">
          <h2><a href="<?php echo $result->response->WebVersionURL ?>" target="_blank">Email Web View</a></h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="container">
        <div class="col-sm-12">
          
        </div>
      </div>
    </div>
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
     <script src="Hello World"></script>
  </body>
</html>