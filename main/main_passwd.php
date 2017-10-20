<?php

if ( (!isset($_SESSION['passmsg'])) || empty($_SESSION['passmsg']) ) $_SESSION['passmsg'] = "";
$msg = $_SESSION['passmsg'];


?>

  <link rel="stylesheet" href="assets/css/formx.css">

  <div class="formx">
      <h1>Ubah password</h1>
      <?php echo $msg; ?>

        <form method="post" action="update-data.php">

            <div class="field-wrap">
              <input type="password" name="cur-pass" placeholder="Current Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="new-pass" placeholder="New Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="re-pass" placeholder="Retype New Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="submit" name="submit-pass" class="button button-block"  value="Submit">
            </div>

            <a class="button button-block button-danger" href="index.php?page=profile" role="button">Kembali</a>

        </form>



  </div> <!-- /form -->

  <script src="assets/js/daftar.js"></script>
