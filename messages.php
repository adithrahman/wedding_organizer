<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php';
require_once 'security.php';
require_once "auth.php";

$auth = new auth();
$db = new Database();
$sec = new Security();


// home mail: every to admin
if (isset($_POST['submit-msg'])){


// wo mail: client/admin to wo
} else if (isset($_POST['submit-wo'])){


// client order : client to wo
} else if (isset($_POST['submit-msg-order'])){
  $wid = $db->getWOEmailByID($_POST['id-wo']);
  $wname = $db->getWONameByID($_POST['id-wo']);
  $cid = $db->getClientEmailByID($_POST['id-cl']);
  $cname = $db->getClientNameByID($_POST['id-cl']);
  $message = $sec->input( $_POST['message'] );

  if ($db->pushMessage($cid,$cname,$wid,$wname,$message)){
    echo "push message order success";
    //header('location:./index.php?page=orders');
    ?>
    <script type="text/javascript">
      window.location.href = 'index.php?page=orders';
    </script>
    <?php
  } else {
    echo "push message order failed";
    //header('location:./index.php?page=orders');
    ?>
    <script type="text/javascript">
      window.location.href = 'index.php?page=orders';
    </script>
    <?php
  }

// wo jobs : wo to client
} else if (isset($_POST['submit-msg-jobs'])){
  $wid = $db->getWOEmailByID($_POST['id-wo']);
  $wname = $db->getWONameByID($_POST['id-wo']);
  $cid = $db->getClientEmailByID($_POST['id-cl']);
  $cname = $db->getClientNameByID($_POST['id-cl']);
  $message = $sec->input( $_POST['message'] );

  if ($db->pushMessage($wid,$wname,$cid,$cname,$message)){
    echo "push message order success";
    //header('location:./index.php?page=jobs');
    ?>
    <script type="text/javascript">
      window.location.href = 'index.php?page=jobs';
    </script>
    <?php
  } else {
    echo "push message order failed";
    //header('location:./index.php?page=jobs');
    ?>
    <script type="text/javascript">
      window.location.href = 'index.php?page=jobs';
    </script>
    <?php
  }

// admin : admin to client
} else if (isset($_POST['submit-msg-admc'])){


// admin : admin to wo
} else if (isset($_POST['submit-msg-admw'])){


// pull chat
} else if (isset($_POST['user_id'])){

  if ($_POST['user_to']=="wo"){
    $wid = $db->getWOEmailByID($_POST['user_id']);
    $wname = $db->getWONameByID($_POST['user_id']);
    $message = $sec->input( $_POST['message'] );
    if ($db->pushMessage("admin","Administrator",$wid,$wname,$message)){
      $db->pullChatAW('admin',$wid);
      //echo "push message order success";
      //header('location:./index.php?page=jobs');
    } else {
      echo "push message order failed";
      //header('location:./index.php?page=jobs');
    }
  } else if ($_POST['user_to']=="cl"){
    $cid = $db->getClientEmailByID($_POST['user_id']);
    $cname = $db->getClientNameByID($_POST['user_id']);
    $message = $sec->input( $_POST['message'] );
    if ($db->pushMessage("admin","Administrator",$cid,$cname,$message)){
      $db->pullChatAC('admin',$cid);
      //echo "push message order success";
      //header('location:./index.php?page=jobs');
    } else {
      echo "push message order failed";
      //header('location:./index.php?page=jobs');
    }

  } else if ( (strpos($_POST['user_to'], 'guest_') !== false) ){
    $ip = str_split($_POST['user_id'],6);
    $ipn = str_replace("_", ".", $ip);
    if ($db->pushMessage("admin","Administrator",$ipn,"Guest [".$ipn."]",$message)){
      $db->pullChatAG('admin',$ipn);
      echo "push message order success";
      //header('location:./index.php?page=jobs');
    } else {
      echo "push message order failed";
      //header('location:./index.php?page=jobs');
    }
    
  // chat to admin handle
  } else if ($_POST['user_to']=="adminc"){
    //$user_from = substr($_POST['data'],0,2);
    //$res = explode($_POST['data']); // get id
    $cid = $db->getClientEmailByID($_POST['data']);
    $cname = $db->getClientNameByID($_POST['data']);
    $message = $sec->input( $_POST['message'] );

    if ($db->pushMessage($cid,$cname,"admin","Administrator",$message)){
      $db->pullChatCA($cid,'admin');
      //echo "push message order success";
      //header('location:./index.php?page=jobs');
    } else {
      echo "push message order failed";
      //header('location:./index.php?page=jobs');
    }

  } else if ($_POST['user_to']=="adminw"){
    //$user_from = substr($_POST['data'],0,2);
    //$res = explode($_POST['data']); // get id
    $wid = $db->getWOEmailByID($_POST['data']);
    $wname = $db->getWONameByID($_POST['data']);
    $message = $sec->input( $_POST['message'] );

    if ($db->pushMessage($wid,$wname,"admin","Administrator",$message)){
      $db->pullChatWA($wid,'admin');
      //echo "push message order success";
      //header('location:./index.php?page=jobs');
    } else {
      echo "push message order failed";
      //header('location:./index.php?page=jobs');
    }

  } else if ($_POST['user_to']=="adming"){
    //$user_from = substr($_POST['data'],0,2);
    //$res = explode($_POST['data']); // get id
    $dec = $sec->encrypt_decrypt("decrypt", $_POST['data']);
    //$dec = $sec->decrypt( $_POST['data'],"" );
    $message = $sec->input( $_POST['message'] );

    if ($db->pushMessage($dec,"Guest [".$dec."]","admin","Administrator",$message)){
      $db->pullChatGA($dec,'admin');
      //echo "push message order success";
      //header('location:./index.php?page=jobs');
    } else {
      echo "push message order failed";
      //header('location:./index.php?page=jobs');
    }
  }
  /*
    if ($user_from == "cl_"){
      $res = explode($_POST['data']); // get id
      $cid = $db->getClientEmailByID($res[1]);
      $cname = $db->getClientNameByID($res[1]);
      if ($db->pushMessage($cid,$cname,"admin","Administrator",$message)){
        $db->pullChatCA($cid,'admin');
        //echo "push message order success";
        //header('location:./index.php?page=jobs');
      } else {
        echo "push message order failed";
        //header('location:./index.php?page=jobs');
      }
    } else if ($user_from == "wo_"){
      $res = explode($_POST['data']); // get id
      $wid = $db->getWOEmailByID($res[1]);
      $wname = $db->getWONameByID($res[1]);
      if ($db->pushMessage($wid,$wname,"admin","Administrator",$message)){
        $db->pullChatWA($wid,'admin']);
        //echo "push message order success";
        //header('location:./index.php?page=jobs');
      } else {
        echo "push message order failed";
        //header('location:./index.php?page=jobs');
      }
    } else {
      $dec = $sec->decrypt( $_POST['data'],"" );
      if ($db->pushMessage($dec,"Guest [".$dec."]","admin","Administrator",$message)){
        $db->pullChatGA($dec, 'admin');
        //echo "push message order success";
        //header('location:./index.php?page=jobs');
      } else {
        echo "push message order failed";
        //header('location:./index.php?page=jobs');
      }
    }
  }
  */
}

?>
