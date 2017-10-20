<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);

require 'database.php';
require 'security.php';
include_once 'modules/securimage/securimage.php';

$db = new Database();
$sec = new Security();
$securimage = new Securimage();

$ref = $_SERVER['HTTP_REFERER'];
//echo $ref; 
if (isset($_POST['submit-clog'])){

    // check captcha secureimage
    if ($securimage->check($_POST['captcha_code']) == true) {

      // receiving the post params
      $email = $sec->input($_POST['email']);
      $password = $sec->input($_POST['pass']);

      // check if user is already existed with the same email
      if ($db->getClientByEmailAndPassword($email,$password) == true) {
        //echo 1;
        $_SESSION['login'] = 1;
        $_SESSION['user'] = $email;
      } else {
        //echo -1;
        $_SESSION['login'] = -1;
      }
    } else {
      //echo -5;
      $_SESSION['login'] = -5;
    }


  header('location:./client.php');
  exit();

} else if (isset($_POST['submit-wlog'])){

      // check captcha secureimage
      if ($securimage->check($_POST['captcha_code']) == true) {

        // receiving the post params
        $email = $sec->input($_POST['email']);
        $password = $sec->input($_POST['pass']);
        $res = $db->getWOByEmailAndPassword($email,$password);

        // check if user is already existed with the same email
        if ($res == true) {
          //echo 1;
          $_SESSION['login'] = 1;
          $_SESSION['user'] = $email;
        } else {
          //echo -1;
          $_SESSION['login'] = -1;
        }
      } else {
        //echo -5;
        $_SESSION['login'] = -5;
      }


    header('location:./wo.php');
    exit();

} else if (isset($_POST['submit-alog'])){
        $admin_name = 'administrator';
        $admin_pass = 'indonesia';

        // receiving the post params
        $name = $sec->input($_POST['name']);
        $password = $sec->input($_POST['pass']);
        //$res = $db->getAdminByEmailAndPassword($email,$password);

        // check if user is already existed with the same email
        //if ($res == true) {
        if (($name == $admin_name) && ($password == $admin_pass)){
          //echo 1;
          $_SESSION['login'] = 1;
          $_SESSION['user'] = $name;
        } else {
          //echo -1;
          $_SESSION['login'] = -1;
        }


      header('location:./admin.php');
      exit();

}

?>
