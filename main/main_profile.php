<?php

if ($auth->isGuest() == false){
  require_once 'database.php';
  require_once 'security.php';

  $db = new Database();
  $sec = new Security();
  $email = $_SESSION['user'];
  $msg="";

  if ($auth->isClient() == true){
    $id = $db->getClientId($_SESSION['user']);
    $name = $db->getClientName($_SESSION['user']);
    $gender = $db->getClientGender($_SESSION['user']);

    if ($gender == "PRIA"){
      $ava = "assets/img/avatarm.png";
    } else {
      $ava = "assets/img/avatarf.png";
    }
    $phone = $db->getClientPhone($_SESSION['user']);
    $address = $db->getClientAddress($_SESSION['user']);

  } else if ($auth->isWO() == true){
    $id = $db->getWOId($_SESSION['user']);
    $desk = $db->getWODeskripsi($_SESSION['user']);
    $name = $db->getWOName($_SESSION['user']);
    $owner = $db->getWOOwner($_SESSION['user']);
    $phone = $db->getWOPhone($_SESSION['user']);
    $address = $db->getWOAddress($_SESSION['user']);

  } else if ($auth->isAdmin() == true){

    header('location:./');
    exit;

  }
}

?>

    <div class="container">
      <div class="jumbotron">

        <table>
          <caption>Profile <?php echo $msg;?></caption>
          <tbody>
            <?php
            if ($auth->isWO() == true){
              echo '<tr>';
                echo '<td colspan="3">';
                  if (file_exists('images/banner/banner_'.$id.'.jpg') )
                    echo '<img class="pbanner" src="images/banner/banner_'.($id).'.jpg" alt="UPLOAD YOUR BANNER HERE!"/>';
                  else {
                    echo '<img class="pbanner" src="assets/img/thumbs/01.jpg" alt="BANNER"/>';
                  }
                    echo '<form class="form-inline" method="post" action="update-data.php" enctype="multipart/form-data">';
                      echo '<div class="form-group">';
                        echo '<input type="file" name="bannerUpload" id="bannerUpload" class="fileb" hidden>';
                          echo '<div class="input-group col-xs-12">';
                            echo '<span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>';
                            echo '<input type="text" class="form-control input-lg" disabled placeholder="Upload Image">';
                            echo '<span class="input-group-btn">';
                              echo '<button class="browseb btn btn-primary input-lg" type="button">';
                                echo '<i class="glyphicon glyphicon-search"></i> Browse';
                              echo '</button>';
                            echo '</span>';
                          echo '</div>';

                        //echo '<input type="file" name="bannerUpload" id="bannerUpload">';
                        //echo '<a href="#" data-toggle="tooltip" title="Ubah banner!">';
                        //  echo '<button type="submit" name="submit-pbnr" class="btn btn-default btn-xs" style="float:right">upload</button>';
                        //echo '</a>';
                      echo '</div>';
                      echo '<button type="submit" name="submit-pbnr" class="btn btn-default" style="float:right">upload</button>';

                    echo '</form>';
                echo '</td>';
              echo '</tr>';
              echo '<tr>';
                echo '<th>Deskripsi</th>';
                echo '<td><textarea id="Deskripsi" rows="2" cols="75" maxlength="150" disabled readonly style="background-color:#fff;border:none;resize:none;">'.$desk.'</textarea></td>';
                //echo '<td id="Deskripsi">'.$desk.'</td>';
                echo '<td>
                        <a href="#" data-toggle="tooltip" title="Ubah deskripsi!">
                          <button type="button" class="btn btn-default btn-xs" id="btn-desk" data-toggle="modal" data-target="#mdl_change" data-title="Deskripsi" data-change="'.$desk.'">
                            <img src="assets/img/write.png"/>
                          </button>
                        </a>
                </td>';
              echo '</tr>';
            }
            ?>
              <tr>
                <th>Nama</th>
                <td id="Nama"> <?php echo $name; ?></td>
                <td>
                  <a href="#" data-toggle="tooltip" title="Ubah nama!">
                    <button type="button" class="btn btn-default btn-xs" id="btn-nama" data-toggle="modal" data-target="#mdl_change" data-title="Nama" data-change="<?php echo $name; ?>">
                      <img src="assets/img/write.png"/>
                    </button>
                  </a>
                </td>
              </tr>
              <?php
              if ($auth->isWO() == true){
                echo '<tr>';
                  echo '<th>Owner</th>';
                  echo '<td id="Owner">'.$owner.'</td>';
                  echo '<td>';
                    echo '<a href="#" data-toggle="tooltip" title="Ubah nama owner!">';
                      echo '<button type="button" class="btn btn-default btn-xs" id="btn-owner" data-toggle="modal" data-target="#mdl_change" data-title="Nama owner" data-change="<?php echo $owner; ?>">';
                        echo '<img src="assets/img/write.png"/>';
                      echo '</button>';
                    echo '</a>';
                  echo '</td>';
                echo '</tr>';
              }
              ?>
              <tr>
                <th>Email</th>
                <td id="Email"> <?php echo $email; ?></td>
                <td>
                  <a href="#" data-toggle="tooltip" title="Ubah email!">
                    <button type="button" class="btn btn-default btn-xs" id="btn-email" data-toggle="modal" data-target="#mdl_change" data-title="Email" data-change="<?php echo $email; ?>">
                      <img src="assets/img/write.png"/>
                    </button>
                  </a>
                </td>
              </tr>
              <tr>
                <th>Phone</th>
                <td id="Phone"> <?php echo $phone; ?></td>
                <td>
                  <a href="#" data-toggle="tooltip" title="Ubah no telepon!">
                    <button type="button" class="btn btn-default btn-xs" id="btn-phone" data-toggle="modal" data-target="#mdl_change" data-title="Telepon" data-change="<?php echo $phone; ?>">
                      <img src="assets/img/write.png"/>
                    </button>
                  </a>
                </td>
              </tr>
              <tr>
                <th>Password</th>
                <td>
                  <b>***</b>
                </td>
                <td>
                  <a href="index.php?page=upass" data-toggle="tooltip" title="Ubah password!">
                    <button type="button" class="btn btn-default btn-xs" id="btn-pass">
                      <img src="assets/img/lock.png"/>
                    </button>
                  </a>
                </td>
              </tr>
              <tr>
                <th>Address</th>
                <td id="Address"><?php echo $address; ?></td>
                <td>
                  <a href="#" data-toggle="tooltip" title="Ubah alamat!">
                    <button type="button" class="btn btn-default btn-xs" id="btn-addr" data-toggle="modal" data-target="#mdl_address" data-title="Alamat" data-change="<?php echo $address; ?>">
                      <img src="assets/img/write.png"/>
                    </button>
                  </a>
                </td>
              </tr>
          </tbody>
        </table>

      </div>
    </div>

    <!-- MODAL !!! -->
    <!-- modal -->
    <div class="modal fade" id="mdl_change" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#555">Change</h4>
          </div>
          <div class="modal-body">
            <form id="pro-form">
              <div class="form-group">
                <label for="recipient-name" class="control-label" style="color:#555">Change:</label>
                <input name="pro-set" id="pro-set" hidden/>
                <input type="text" class="form-control" name="pro-ctrl" id="pro-ctrl" style="color:#555" required autocomplete="off">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="close-ctrl" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submit-ctrl" name="submit-ctrl">Save changes</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="mdl_address" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#555">Change</h4>
          </div>
          <div class="modal-body">
            <form id="addr-form">
              <div class="form-group">
                <label for="recipient-name" class="control-label" style="color:#555">Change:</label>
                <input name="addr-set" id="addr-set" hidden/>
                <textarea class="form-control" id="addr-ctrl" name="addr-ctrl" required autocomplete="off"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="close-addr" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submit-addr" name="submit-addr">Save changes</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <link rel="stylesheet" href="assets/css/main_profile.css" />
