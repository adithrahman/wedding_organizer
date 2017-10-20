<?php
  require_once 'database.php';
  require_once 'security.php';

  $db = new Database();
  $sec = new Security();

  $pid = $_GET['o'];
?>


    <div class="container">
      <div class="jumbotron">

        <h1>Order Paket<strong><?php echo $db->getWOPackageByID($pid); ?></strong></h1>

      </div>
    </div>

    <link rel="stylesheet" href="assets/css/main_profile.css" />
