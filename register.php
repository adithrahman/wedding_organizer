<?php
session_start();
// Turn off all error reporting
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors',1);

//include_once 'database.php';
include_once 'security.php';
include_once 'mail.php';
include_once 'modules/securimage/securimage.php';

$mail = new Mailer();
$db = new Database();
$sec = new Security();
$securimage = new Securimage();
$ref = $_SERVER['HTTP_REFERER'];

if (strpos($ref, 'client') !== false) {
  if (isset($_POST['submit'])){

    // check captcha secureimage
    if ($securimage->check($_POST['captcha_code'])) {

      // receiving the post params
      $name = $sec->input($_POST['name']);
      $email = $sec->input($_POST['email']);
      $phone = $sec->input($_POST['phone']);
      $address = $sec->input($_POST['address']);
      $gender = $_POST['gender'];
      $password = $sec->input($_POST['pass']);

      if ($_POST['pass'] == $_POST['repass']){
        // check if user is already existed with the same email
        if ($db->isClientExisted($email)) {
          //echo -1;
          $_SESSION['register'] = -1;
        } else {
          // create a new user
          $user = $db->storeClient($name, $gender, $email, $phone, $address, $password);
          if ($user) {
            //echo 1;
            //include_once 'security.php';
            //$sec = new Security();

            $enc1 = $sec->encrypt_decrypt("encrypt", $email);
            $enc2 = $sec->encrypt_decrypt("encrypt", "client");
            
            
            $subj = "Welcome to WO Syar'i";
            $msg = "Please click link button bellow to confirm your email address! ";
            $url = "http://wo.sibershield.com?cm=".$enc1."&cw=".$enc2;
            
            $mail->sendMailToClient($email,$subj,$msg.$url);
            
            /*
            $to      = $email;
            $subject = $subj;
            $message = $msg;
            $headers = 'From: info@wo.sibershield.com' . "\r\n" .
                'Reply-To: info@wo.sibershield.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            ini_set ( "SMTP", "mail.wo.sibershield.com" ); 
            date_default_timezone_set('Asia/Jakarta');

            mail($to, $subject, $message, $headers);
            */
            
            $_SESSION['register'] = 1;
          } else {
            //echo -2;
            $_SESSION['register'] = -2;
          }
        }
      } else {
        //echo -4;
        $_SESSION['register'] = -4;
      }
    } else {
      //echo -5;
      $_SESSION['login'] = -5;
    }

  } else {
    //echo -3;
    $_SESSION['register'] = -3;
  }

  header('location:./client.php');
  //echo "<meta http-equiv='refresh' content='0;./client.php'>";
  //echo "<body onload='window.location=./client.php'>";
  //exit();

} else if (strpos($ref, 'wo') !== false) {
  if (isset($_POST['submit'])){

    // check captcha secureimage
    if ($securimage->check($_POST['captcha_code']) == true) {

      // receiving the post params
      $name = $sec->input($_POST['name']);
      $owner = $sec->input($_POST['owner']);
      $email = $sec->input($_POST['email']);
      $phone = $sec->input($_POST['phone']);
      $address = $sec->input($_POST['address']);
      $password = $sec->input($_POST['pass']);

      if ($_POST['pass'] == $_POST['repass']){
        // check if user is already existed with the same email
        if ($db->isWOExisted($email)) {
          //echo -1;
          $_SESSION['register'] = -1;
        } else {
          // create a new user
          $user = $db->storeWO($name, $owner, $email, $phone, $address, $password);
          if ($user) {
            //echo 1;
            $_SESSION['register'] = 1;
          } else {
            //echo -2;
            $_SESSION['register'] = -2;
          }
        }
      } else {
        //echo -4;
        $_SESSION['register'] = -4;
      }

    } else {
      //echo -5;
      $_SESSION['login'] = -5;
    }

  } else {
    //echo -3;
    $_SESSION['register'] = -3;
  }

  header('location:./wo.php');
  //echo "<meta http-equiv='refresh' content='0;./wo.php'>";
  //echo "<body onload='window.location=./wo.php'>";
  //exit();

} else if (strpos($ref, 'admin') !== false) {

}

?>
