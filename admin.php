<?php
session_start();
$msg = '';

//----- LOGIN HANDLE
if ($_SESSION['login'] == -1){
  $msg = '<label class="msg msg-danger">Email or Password not match!</label>';
} else if ($_SESSION['login'] == -3){
  $msg = '<label class="msg msg-danger">Something wrong!</label>';
} else if ($_SESSION['login'] == 1){
  $msg = '<label class="msg msg-success">Congratulation !</label>';
  $_SESSION['visitor'] = 'admin';
  header('location:./');
  exit();
}
$_SESSION['login'] = 0;

?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign-Up/Login Form</title>
  <meta charset="UTF-8">

  <!--link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'-->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/daftar.css">

</head>

<body>

  <div class="form">
      <h1>ADMINISTRATOR</h1>
      <?php echo $msg; ?>


        <div id="masuk">

          <form method="post" action="login.php">

            <div class="field-wrap">
              <input type="text" name="name" placeholder="Name" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="pass" placeholder="Password" required autocomplete="off"/>
            </div>


            <div class="field-wrap">
              <input type="submit" name="submit-alog" class="button button-block"  value="Masuk">
            </div>

            <a class="button button-block button-danger" href="index.php" role="button">Kembali</a>

          </form>


        </div>


    </div> <!-- /form -->

  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/daftar.js"></script>

</body>
</html>
