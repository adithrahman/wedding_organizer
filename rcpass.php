<?php

require_once "auth.php";
require_once 'security.php';
require 'mail.php';

$auth = new auth();
$sec = new Security();
$mail = new Mailer();
    
  $msg = "";
  
  if (isset($_POST['submit-rrpass'])) {

    $enc1 = $sec->encrypt_decrypt("encrypt", $_POST['email']);
    $enc2 = $sec->encrypt_decrypt("encrypt", "client");
    
    $email = $sec->input($_POST['email']);
    $subj = "Reset Password";
    $msgb = "Please click link button bellow to reset your password! ";
    $url = "http://wo.sibershield.com/rcpass.php?reset=".$enc1."&cp=".$enc2;

    $mail->sendMailToClient($email,$subj,$msgb.$url);
    $msg = "Please check your email !";
    //} else {
    //    $msg = "Password restore failed !";
    //}
  }
  
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <meta charset="UTF-8">

  <!--link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'-->
  <link rel="icon" href="favicon.png" type="image/x-icon" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/formx.css">

</head>

<body>

<?php
if ($auth->isGuest() == true){
  if (isset($_GET['reset'])){
      $dec = $sec->encrypt_decrypt("decrypt", $_GET['reset']);
?>

    <div class="formx">
      <h1>Reset password</h1>
      <?php echo $msg; ?>

        <form method="post" action="update-data.php">

            <div class="field-wrap">
              <input type="hidden" name="client" value="<?php echo $_GET['reset'];?>" hidden/>
            </div>

            <div class="field-wrap">
              <input type="password" name="new-pass" placeholder="New Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="re-pass" placeholder="Retype New Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="submit" name="submit-rcpass" class="button button-block"  value="Submit">
            </div>

            <a class="button button-block button-danger" href="index.php" role="button">Kembali</a>

        </form>
    </div> <!-- /form -->
<?php
  } else {
?>
    <div class="formx">
      <h1>Reset password</h1>
      <?php echo $msg; ?>

        <form method="post" action="">

            <div class="field-wrap">
              <input type="email" name="email" placeholder="Put your email here" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="submit" name="submit-rrpass" class="button button-block" value="Reset">
            </div>

            <a class="button button-block button-danger" href="index.php" role="button">Kembali</a>

        </form>

      </div> <!-- /form -->
<?php
  }
} else {
  include "error/404.html";
}
?>
  <script src="assets/js/daftar.js"></script>
</body>
</html>
