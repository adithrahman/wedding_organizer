<?php
session_start();
$msg = '';

//----- REGISTER HANDLE
if ($_SESSION['register'] == -1){
  $msg = '<label class="msg msg-danger">This email already registered</label>';
} else if ($_SESSION['register'] == -2){
  $msg = '<label class="msg msg-danger">Fill the box correctly!</label>';
} else if ($_SESSION['register'] == -3){
  $msg = '<label class="msg msg-danger">Something wrong!</label>';
} else if ($_SESSION['register'] == -4){
  $msg = '<label class="msg msg-danger">Password miss match!</label>';
} else if ($_SESSION['login'] == -5){
  $msg = '<label class="msg msg-warning">Captcha image wrong!</label>';
} else if ($_SESSION['register'] == 1){
  $msg = '<label class="msg msg-success">Congratulation ! Please check your email</label>';
}
$_SESSION['register'] = 0;

//----- LOGIN HANDLE
if ($_SESSION['login'] == -1){
  $msg = '<label class="msg msg-danger">Email or Password not match!</label>';
} else if ($_SESSION['login'] == -3){
  $msg = '<label class="msg msg-danger">Something wrong!</label>';
} else if ($_SESSION['login'] == -5){
  $msg = '<label class="msg msg-warning">Captcha image wrong!</label>';
} else if ($_SESSION['login'] == 1){
  $msg = '<label class="msg msg-success">Congratulation !</label>';
  $_SESSION['visitor'] = 'wo';
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
      <h1>Weeding Organizer</h1>
      <?php echo $msg; ?>
      <ul class="tab-group">
        <li class="tab active"><a href="#daftar">Daftar</a></li>
        <li class="tab"><a href="#masuk">Masuk</a></li>
      </ul>

      <div class="tab-content">
        <div id="daftar">

          <form method="post" action="register.php">

            <div class="field-wrap">
              <input type="text" name="name" placeholder="Nama WO *" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <input type="text" name="owner" placeholder="Nama Owner *" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <input type="email" name="email" placeholder="Email *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="text" name="phone" placeholder="Telepon *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="text" name="address" placeholder="Address *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="pass" placeholder="Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="repass" placeholder="Retype Password *" required autocomplete="off"/>
            </div>

            <div class="field-wrap">

                <img id="captcha" src="modules/securimage/securimage_show.php" alt="CAPTCHA Image" />
                <a href="#" data-toggle='tooltip' title='Refresh Image' onclick="document.getElementById('captcha').src = 'modules/securimage/securimage_show.php?' + Math.random(); return false"><img src="modules/securimage/images/refresh.png" alt="Refresh Image" onclick="this.blur()" style="border: 0px; vertical-align: bottom" height="32" width="32"></a>
                <p style="margin-top:10px;">
                  <input type="text" name="captcha_code" placeholder="CAPTCHA Code" size="10" maxlength="6" required autocomplete="off"/>
                </p>

            </div>

            <div class="field-wrap">
              <input type="submit" name="submit" class="button button-block"  value="Daftar">
            </div>

            <a class="button button-block button-danger" href="index.php" role="button">Kembali</a>

          </form>

        </div>

        <div id="masuk">

          <form method="post" action="login.php">

            <div class="field-wrap">
              <input type="email" name="email" placeholder="Email" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <input type="password" name="pass" placeholder="Password" required autocomplete="off"/>
            </div>

            <p class="forgot"><a href="rwpass.php">Forgot Password?</a></p>

            <div class="field-wrap">

                <img id="captcha" src="modules/securimage/securimage_show.php" alt="CAPTCHA Image" />
                <a href="#" data-toggle='tooltip' title='Refresh Image' onclick="document.getElementById('captcha').src = 'modules/securimage/securimage_show.php?' + Math.random(); return false"><img src="modules/securimage/images/refresh.png" alt="Refresh Image" onclick="this.blur()" style="border: 0px; vertical-align: bottom" height="32" width="32"></a>
                <p style="margin-top:10px;">
                  <input type="text" name="captcha_code" placeholder="CAPTCHA Code" size="10" maxlength="6" required autocomplete="off"/>
                </p>

            </div>

            <div class="field-wrap">
              <input type="submit" name="submit-wlog" class="button button-block"  value="Masuk">
            </div>

            <a class="button button-block button-danger" href="index.php" role="button">Kembali</a>

          </form>


        </div>

      </div><!-- tab-content -->

</div> <!-- /form -->

  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/daftar.js"></script>

</body>
</html>
