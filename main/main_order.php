<?php
require_once "auth.php";
$auth = new auth();

if ($auth->isGuest() == false){
  require_once 'database.php';
  require_once 'security.php';

  $db = new Database();
  $sec = new Security();
  $email = $_SESSION['user'];
  $msg="";

  if ($auth->isClient() == true){
    $id = $db->getClientId($_SESSION['user']);
    $oid = $db->getClientOrderIdByCId($id);
    $wid = $db->getClientWOIdByCId($id);
    $cname = $db->getClientNameByID($id);
    $wname = $db->getClientOrderWName($id);
    $pname = $db->getClientOrderPName($id);
    $price = $db->getClientOrderPrice($id);
    $capacity = $db->getClientOrderCapacity($id);
    $status = $db->getClientOrderStatus($id);
    $payment = $db->getClientOrderPayment($id);
    $pay_name = $db->getClientOrderPayName($id);
      if ($pay_name == "") $pay_name = "Upload Image (jpg or jpeg)";
    $dateo = $db->getClientOrderDO($id);


    if (!empty($_SESSION['up-data']) ){
      $gb->showInfo("Failed", $_SESSION['up-data']);
      $_SESSION['up-data'] = "";
    }
  }
}
?>


    <div class="container">
      <div class="jumbotron">

      <?php
        if ($db->isClientHaveOrder($id) > 0){
          if ($status == 'APPROVE'){
            echo '<div class="panel panel-primary">';
          } else if ($status == 'NO ACTION'){
            echo '<div class="panel panel-default">';
          } else if ($status == 'DENIED'){
            echo '<div class="panel panel-danger">';
          } else if ($status == 'PENDING'){
            echo '<div class="panel panel-info">';
          } else if ($status == 'COMPLETE'){
            echo '<div class="panel panel-success">';
          }
        ?>
          <div class="panel-heading">
            Order: <strong id="strongz"><?php echo $pname;?></strong>
          </div>

          <div class="panel-body">
          <div class="row">
            <div class="col-md-6">

              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <tr>
                      <th>Paket</th>
                      <td><?php echo $pname;?></td>
                    </tr>
                    <tr>
                      <th>Harga</th>
                      <td>Rp.<?php echo $price;?></td>
                    </tr>
                    <tr>
                      <th>Kapasitas</th>
                      <td><?php echo $capacity;?></td>
                    </tr>
                    <tr>
                      <th>Fasilitas</th>
                      <td>lol</td>
                    </tr>
                    <tr>
                      <th>Status</th>
                      <td><?php echo $status;?></td>
                    </tr>
                    <tr>
                      <th>Tanggal order</th>
                      <td><?php echo $dateo;?></td>
                    </tr>
                    <tr>
                      <th>Pekerjaan selesai</th>
                      <td>lol</td>
                    </tr>
                    <tr>
                      <th>Pembayaran</th>
                      <td><?php echo $payment;?></td>
                    </tr>
                    <tr>
                      <th>Upload bukti transfer</th>
                      <td>
                        <div class="form-group">
                          <?php
                          if ($pay_name!="Upload Image (jpg or jpeg)"){
                            echo '<img class="pay-img" src="images/payment/'.$pay_name.'" width="300px" height="150px">';
                          }
                          ?>
                          <div class="input-group col-xs-12">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                            <input type="text" name="pay_name" id="pay_name" class="form-control input-lg" disabled placeholder="<?php echo $pay_name;?>">
                            <span class="input-group-btn">
                              <button class="browse btn btn-primary input-lg" type="button">
                                <i class="glyphicon glyphicon-search"></i> Browse
                              </button>
                            </span>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php
                    if ($status != 'COMPLETE'){
                     ?>
                    <tr>
                      <td colspan="2">
                        <form action="update-data.php" method="post" enctype="multipart/form-data">
                          <input type="text" name="id-paket" id="id-paket" value="<?php echo $oid;?>" hidden=""/>
                          <input type="file" name="payment" id="payment" class="file">
                          <button class="btn btn-info" type="submit" name="submit-order">SAVE</button>
                          <button class="btn btn-danger" type="button" data-toggle="modal" data-title="Cancel order" data-msg="cancel order ?" data-target="#mdl_confirm" data-href="index.php?page=delo&o1=<?php echo $id;?>&o2=<?php echo $oid;?>">CANCEL ORDER</button>
                        </form>
                      </td>
                    </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!-- table resposive -->

            </div>

            <div class="col-md-6">

              <div class="chat-box">
                <div class="msg-box">
                  <?php
                    $db->pullMessage($wid,$id);
                  ?>
                </div>
                <form class="form-inline" action="messages.php" method="post">
                  <div class="form-group" style="width:100%">
                    <input type="text" name="id-wo" id="id-wo" value="<?php echo $wid;?>" hidden=""/>
                    <input type="text" name="id-cl" id="id-cl" value="<?php echo $id;?>" hidden=""/>

                    <textarea class ="form-control" name="message" placeholder="Message" rows="2" style="resize: none; width:100%; margin-top:5px;" required=""></textarea>
                      <button style="margin-top:5px;" class="btn btn-default" type="submit" name="submit-msg-order">
                        POST
                      </button>
                  </div>
                </form>
              </div>

            </div>

          </div>
        </div> <!-- panel-body -->

          <!--
          <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <td colspan="2">PAKET Info</td>
                <td rowspan="11" >
                  <h3>Wo Messages - <?php //echo $wname;?></h3>
                    <div class="msg-box">
                      <?php
                        //$db->pullMessage($wid,$id);
                      ?>
                    </div>
                    <form class="form-inline" action="messages.php" method="post">
                      <div class="form-group" style="width:100%">
                        <input type="text" name="id-wo" id="id-wo" value="<?php //echo $wid;?>" hidden=""/>
                        <input type="text" name="id-cl" id="id-cl" value="<?php //echo $id;?>" hidden=""/>

                        <textarea class ="form-control" name="message" placeholder="Message" rows="2" style="resize: none; width:100%; margin-top:5px;" required=""></textarea>
                          <button style="margin-top:5px;" class="btn btn-default" type="submit" name="submit-msg-order">
                            POST
                          </button>
                      </div>
                    </form>
                  </div>
                </td></tr>
                <tr>
                  <th>Paket</th>
                  <td><?php //echo $pname;?></td>
                </tr>
                <tr>
                  <th>Harga</th>
                  <td>Rp.<?php //echo $price;?></td>
                </tr>
                <tr>
                  <th>Kapasitas</th>
                  <td><?php //echo $capacity;?></td>
                </tr>
                <tr>
                  <th>Fasilitas</th>
                  <td>lol</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td><?php //echo $status;?></td>
                </tr>
                <tr>
                  <th>Tanggal order</th>
                  <td><?php //echo $dateo;?></td>
                </tr>
                <tr>
                  <th>Pekerjaan selesai</th>
                  <td>lol</td>
                </tr>
                <tr>
                  <th>Pembayaran</th>
                  <td><?php //echo $payment;?></td>
                </tr>
                <tr>
                  <th>Upload bukti transfer</th>
                  <td>
                    <div class="form-group">
                      <?php
                      //if ($pay_name!="Upload Image (jpg or jpeg)"){
                      //  echo '<img src="images/payment/'.$pay_name.'" width="300px" height="150px">';
                      //}
                      ?>
                      <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                        <input type="text" name="pay_name" id="pay_name" class="form-control input-lg" disabled placeholder="<?php echo $pay_name;?>">
                        <span class="input-group-btn">
                          <button class="browse btn btn-primary input-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i> Browse
                          </button>
                        </span>
                      </div>
                    </div>
                  </td>
                </tr>
                <?php
                //if ($status != 'COMPLETE'){
                 ?>
                <tr>
                  <td colspan="2">
                    <form action="update-data.php" method="post" enctype="multipart/form-data">
                      <input type="text" name="id-paket" id="id-paket" value="<?php //echo $oid;?>" hidden=""/>
                      <input type="file" name="payment" id="payment" class="file">
                      <button class="btn btn-info" type="submit" name="submit-order">SAVE</button>
                      <button class="btn btn-danger" type="button" data-toggle="modal" data-title="Cancel order" data-msg="cancel order ?" data-target="#mdl_confirm" data-href="index.php?page=delo&o1=<?php echo $id;?>&o2=<?php echo $oid;?>">CANCEL ORDER</button>
                    </form>
                  </td>
                </tr>
                <?php
                //}
                ?>
              </tbody>
            </table>
          </div>
        -->

        </div>

        <?php
      } else {
        echo "No Order";
      }
        ?>

      </div>
    </div>

    <link rel="stylesheet" href="assets/css/main_profile.css" />
