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

$email = $_SESSION['user'];

// -- SET PROFILE
if (isset($_POST['pro-set'])){
  if ($auth->isClient()){
    if ($sec->input($_POST['pro-set']) == "Nama"){
      if ($db->setClientName($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    } else if ($sec->input($_POST['pro-set']) == "Email"){
      if ($db->setClientPhone($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    } else if ($sec->input($_POST['pro-set']) == "Telepon"){
      if ($db->setClientPhone($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    }
  } else if ($auth->isWO()){
    if ($sec->input($_POST['pro-set']) == "Deskripsi"){
      if ($db->setWODesk($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    } else if ($sec->input($_POST['pro-set']) == "Nama"){
      if ($db->setWOName($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    } else if ($sec->input($_POST['pro-set']) == "Email"){
      if ($db->setWOPhone($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    } else if ($sec->input($_POST['pro-set']) == "Telepon"){
      if ($db->setWOPhone($email,$sec->input($_POST['pro-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    }
  }
// -- SET ADDRESS
} else if (isset($_POST['addr-set'])){
    if ($auth->isClient()){
      if ($db->setClientAddress($email,$sec->input($_POST['addr-ctrl']))){
        echo "success!";
        //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
      } else {
        echo "unsuccess!";
        //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
      }
    } else if ($auth->isWO()){
        if ($db->setWOAddress($email,$sec->input($_POST['addr-ctrl']))){
          echo "success!";
          //$msg = '<label class="msg msg-success">'.$_POST['pro-set'].' changed !</label>';
        } else {
          echo "unsuccess!";
          //$msg = '<label class="msg msg-danger">'.$_POST['pro-set'].' not changed!</label>';
        }
    }
// -- SUBMIT CHANGE PROFILE PASSWORD
} else if (isset($_POST['submit-pass'])){
  if (!empty($_POST['new-pass']) && !empty($_POST['new-pass']) && !empty($_POST['new-pass'])){
    if ($_POST['new-pass'] == $_POST['re-pass']){
      if ($auth->isClient()){
        if ($db->checkClientPass($email,$sec->input($_POST['cur-pass']))){
          if ($db->setClientPass($email,$sec->input($_POST['new-pass']))){
            echo "succes change client pass!";
            //return "success!";
            $_SESSION['passmsg'] = '<label class="msg msg-success">Password changed !</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
          } else {
            echo "unsuccess change client pass!";
            //return "unsuccess!";
            $_SESSION['passmsg'] = '<label class="msg msg-danger">Password not changed!</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
          }
        } else{
          echo "unsuccess current pass!!";
          //return "unsuccess!";
          $_SESSION['passmsg'] = '<label class="msg msg-danger">Current password not match!</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
        }
      } else if ($auth->isWO()){
        if ($db->checkWOPass($email,$sec->input($_POST['cur-pass']))){
          if ($db->setWOPass($email,$sec->input($_POST['new-pass']))){
            echo "succes change wo pass!";
            //return "success!";
            $_SESSION['passmsg'] = '<label class="msg msg-success">Password changed !</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
          } else {
            echo "unsuccess change wo pass!";
            //return "unsuccess!";
            $_SESSION['passmsg'] = '<label class="msg msg-danger">Password not changed!</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
          }
        } else{
          echo "unsuccess current pass!!";
          //return "unsuccess!";
          $_SESSION['passmsg'] = '<label class="msg msg-danger">Current password not match!</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
        }
      }
    } else {
      echo "unsuccess new password not match!";
      $_SESSION['passmsg'] = '<label class="msg msg-danger">New password not match!</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
    }
  } else {
    echo "unsuccess variable empty!";
    $_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?upass.php';
    </script>
    <?php
  }
// -- RESET CLIENT PASSWORD
} else if (isset($_POST['submit-rcpass'])){
  if ((!empty($_POST['new-pass'])) && (!empty($_POST['re-pass']))){
    $dec = $sec->encrypt_decrypt("decrypt", $_POST['client']);
    if ($db->setClientPass($dec,$sec->input($_POST['new-pass']))){
      //echo "succes change client pass!";
      //return "success!";
      //$_SESSION['passmsg'] = '<label class="msg msg-success">Password changed !</label>';
      $cs = $sec->encrypt_decrypt("encrypt", "success");
      //header('location:./index.php?page=rcpass&cn='. $_POST['client'] .'&cs=1');
      ?>
      <script type="text/javascript">
        window.location.href = 'index.php?page=rwpass&wn='. $_POST['client'] .'&cs=0';
      </script>
      <?php
    } else {
      //echo "unsuccess change client pass!";
      //return "unsuccess!";
      //$_SESSION['passmsg'] = '<label class="msg msg-danger">Password not changed!</label>';
      $cs = $sec->encrypt_decrypt("encrypt", "unsuccess");
      //header('location:./index.php?page=rcpass&cn='. $_POST['client'] .'&cs=0');
      ?>
      <script type="text/javascript">
        window.location.href = 'index.php?page=rwpass&wn='. $_POST['client'] .'&cs=0';
      </script>
      <?php
    }
  } else {
    //echo "unsuccess variable empty!";
    //$_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?rcpass.php');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?rcpass.php';
    </script>
    <?php
  }
// -- RESET WO PASSWORD
} else if (isset($_POST['submit-rwpass'])){
  if ((!empty($_POST['new-pass'])) && (!empty($_POST['re-pass']))){
    $dec = $sec->encrypt_decrypt("decrypt", $_POST['wo']);
    if ($db->setWOPass($dec,$sec->input($_POST['new-pass']))){
      //echo "succes change wo pass!";
      //return "success!";
      //$_SESSION['passmsg'] = '<label class="msg msg-success">Password changed !</label>';
      $ws = $sec->encrypt_decrypt("encrypt", "success");
      //header('location:./index.php?page=rwpass&wn='. $_POST['wo'] .'&ws=1');
      ?>
      <script type="text/javascript">
        window.location.href = 'index.php?page=rwpass&wn='. $_POST['wo'] .'&ws=1';
      </script>
      <?php
    } else {
      //echo "unsuccess change wo pass!";
      //return "unsuccess!";
      //$_SESSION['passmsg'] = '<label class="msg msg-danger">Password not changed!</label>';
      $ws = $sec->encrypt_decrypt("encrypt", "unsuccess");
      //header('location:./index.php?page=rwpass&wn='. $_POST['wo'] .'&ws=0');
      ?>
      <script type="text/javascript">
        window.location.href = 'index.php?page=rwpass&wn='. $_POST['wo'] .'&ws=0';
      </script>
      <?php
    }
  } else {
    //echo "unsuccess variable empty!";
    //$_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?rcpass.php');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php?rcpass.php';
    </script>
    <?php
  }
  
// -- PACKAGE store
} else if (isset($_POST['pckg-name'])){
  if ( (!empty($_POST['pckg-name'])) && (!empty($_POST['pckg-cpct'])) && (!empty($_POST['pckg-price'])) ){

    //echo "Init WO Package</br>"
      if ($auth->isWO()){
        $id = $db->getWOId($email);

          if ($db->storeWOPackage($id, $sec->input($_POST['pckg-name']), $sec->input($_POST['pckg-price']), $sec->input($_POST['pckg-cpct'])) ){
            echo "success";
          } else{
            echo "unsuccess";
          }

      }

  } else {
    echo "unsuccess variable empty!";
    //$_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?page=upass');
  }

// -- PACKAGE update

} else if (isset($_POST['pupdate-name'])){
  if ( (!empty($_POST['pupdate-name'])) && (!empty($_POST['pupdate-cpct'])) && (!empty($_POST['pupdate-price'])) ){

      if ($auth->isWO()){
        if ( (isset($_POST['pupdate-oid'])) && (isset($_POST['pupdate-oname'])) &&
            (!empty($_POST['pupdate-oid'])) && (!empty($_POST['pupdate-oname'])) ){

            if ($db->updateWOPackages($sec->input($_POST['pupdate-oid']), $sec->input($_POST['pupdate-name']), $sec->input($_POST['pupdate-price']), $sec->input($_POST['pupdate-cpct']),
                                      $sec->input($_POST['pupdate-oid']), $sec->input($_POST['pupdate-oname']))
                                    ){
                                      echo "update success";
                                    } else {
                                      echo "update unsuccess";
                                    }
        } else{
          echo "isset variables"."</br>";}
      } else {
        echo "not WO"."</br>";}

  } else {
    echo "unsuccess variable empty!";
    //$_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?page=upass');
  }

// -- FASILITAS store

} else if ( (isset($_POST['fasilitas-paket'])) && (isset($_POST['fasilitas-item'])) ){
  if ( (!empty($_POST['fasilitas-item'])) && (!empty($_POST['fasilitas-total'])) ){

    if ($auth->isWO()){

        if ($db->storeWOFasilitas($sec->input($_POST['fasilitas-paket']), $sec->input($_POST['fasilitas-tipe']),
                                  $sec->input($_POST['fasilitas-item']), $sec->input($_POST['fasilitas-total'])) ) {
          echo "store success";
        } else {
          echo "store unsuccess";
        }

    }

  } else {
    echo "unsuccess variable empty!";
    //$_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?page=upass');
  }

// -- FASILITAS update

} else if ( (isset($_POST['fasilitasu-paket'])) && (isset($_POST['fasilitasu-item'])) ){
  if ( (!empty($_POST['fasilitasu-item'])) && (!empty($_POST['fasilitasu-total'])) ){

    if ($auth->isWO()){

      if ( (isset($_POST['fasilitasu-paketo'])) && (isset($_POST['fasilitasu-itemo'])) &&
          (!empty($_POST['fasilitasu-paketo'])) && (!empty($_POST['fasilitasu-itemo'])) ) {

          if ($db->updateWOFasilitas($sec->input($_POST['fasilitasu-paket']), $sec->input($_POST['fasilitasu-tipe']),
                                    $sec->input($_POST['fasilitasu-item']), $sec->input($_POST['fasilitasu-total']),
                                    $sec->input($_POST['fasilitasu-paketo']), $sec->input($_POST['fasilitasu-itemo']))
                                  ){
                                    echo "update success";
                                  } else {
                                    echo "update unsuccess";
                                  }
      }
    }

  } else {
    echo "unsuccess variable empty!";
    //$_SESSION['passmsg'] = '<label class="msg msg-danger">Please fill the box!</label>';
    //header('location:./index.php?page=upass');
  }

// -- CHANGE BANNER
} else if (isset($_POST['submit-pbnr'])) {
  if ($auth->isWO() == true){
    if (isset($_FILES['bannerUpload'])){
      $errors= array();
      $id = $db->getWOId($email);

      $target_dir = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . "/images/banner/";
      $target_file = $target_dir."banner_". $id.".jpg";
      //$target_file = $target_dir . basename($_FILES['bannerUpload']['name']);

      $file_name = $_FILES['bannerUpload']['name'];
      $file_size = $_FILES['bannerUpload']['size'];
      $file_tmp = $_FILES['bannerUpload']['tmp_name'];
      $file_type = $_FILES['bannerUpload']['type'];
      //$file_ext=strtolower(end(explode('.',$_FILES['bannerUpload']['name'])));
      $file_ext = pathinfo($_FILES['bannerUpload']['name'],PATHINFO_EXTENSION);

      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

      // cek directories
      if (is_dir($target_dir) == false){
         $errors[]="Folder doesn't exists!";
      }

      // cek writable directories
      if (is_writable($target_dir) == false){
         $errors[]="Folder not writable!";
      }

      // cek ekstensi
      $expensions= array("jpeg","jpg");
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG file.";
      }

      // cek image size < 2MB !!!
      if($file_size > 2097152) {
         $errors[]='File size must be excately 2 MB';
      }

      // pindahkan file banner
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,$target_file);
         echo "Success";
        //header('location:./index.php?page=profile');
        ?>
        <script type="text/javascript">
            window.location.href = 'index.php?page=profile';
        </script>
        <?php
         //exit():
      }else{
         print_r($errors);
        //header('location:./index.php?page=profile');
        ?>
        <script type="text/javascript">
            window.location.href = 'index.php?page=profile';
        </script>
        <?php
         //exit():
      }

    }
  }

// -- CHANGE JOB
} else if (isset($_POST['submit-job'])) {
    $arg2 = "in-pkt-".$_POST["id-paket"];
    $db->updateJobStatus($_POST["id-paket"],$_POST[$arg2]);
    header('location:./index.php?page=jobs');
// -- CHANGE JOB
} else if (isset($_POST['submit-order'])) {
  if (isset($_FILES['payment'])){
    $errors= array();

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . "/images/payment/";
    $target_file = $_POST["id-paket"].".jpg";
    $image = addslashes(file_get_contents($_FILES['payment']['tmp_name']));

    $file_name = $_FILES['payment']['name'];
    $file_size = $_FILES['payment']['size'];
    $file_tmp = $_FILES['payment']['tmp_name'];
    $file_type = $_FILES['payment']['type'];
    //$file_ext=strtolower(end(explode('.',$_FILES['bannerUpload']['name'])));
    $file_ext = pathinfo($_FILES['payment']['name'],PATHINFO_EXTENSION);

    // cek ekstensi
    $expensions= array("jpeg","jpg");
    if(in_array($file_ext,$expensions)=== false){
       $errors[]="extension not allowed, please choose a JPEG file.";
    }

    // cek image size < 50kb !!!
    if($file_size > 500000) {
       $errors[]='File size must be excately under 50 KB';
    }

    // pindahkan file banner
    if(empty($errors)==true) {
        //echo $image;
        move_uploaded_file($file_tmp,$target_dir.$target_file);
        $db->updateOrderPayment($_POST["id-paket"],$target_file);
        print_r($errors);
        $_SESSION['up-data'] = "";
        //header('location:./index.php?page=orders');
        ?>
        <script type="text/javascript">
            window.location.href = 'index.php?page=orders';
        </script>
        <?php
        //exit():
    }else{
        print_r($errors);
        $_SESSION['up-data'] = $errors;
        //header('location:./index.php?page=orders');
        ?>
        <script type="text/javascript">
            window.location.href = 'index.php?page=orders';
        </script>
        <?php
        //exit():
    }
  } else {
    echo "file loss";
  }
}

?>
