<?php

if ($auth->isGuest() == false){
  include 'function.php';
  include_once 'database.php';
  include 'security.php';

  $db = new Database();
  $gb = new Globalz();
  $sec = new Security();

  if ($auth->isClient() == true){
    $id = $db->getClientId($_SESSION['user']);
    $name = $db->getClientName($_SESSION['user']);
    $gender = $db->getClientGender($_SESSION['user']);

    if ($gender == "PRIA"){
      $ava = "assets/img/avatarm.png";
    } else {
      $ava = "assets/img/avatarf.png";
    }
  } else if ($auth->isWO() == true){
    $id = $db->getWOId($_SESSION['user']);
    $name = $db->getWOName($_SESSION['user']);
  } else if ($auth->isAdmin() == true){
    //$id = $db->getAdminId($_SESSION['user']);
    //$name = $db->getAdminName($_SESSION['user']);
    $id=0;$name='administrator';
  }
}

?>



		<!-- Page Wrapper -->
			<div id="page-wrapper">

        <!-- panel kanan start -->
				<!-- Header -->
					<header id="header" class="alt">
						<h1><a href="">Menikah Syar'i</a></h1>
						<nav id="nav">
							<ul>
								<li class="special">
									<a href="#menu" class="menuToggle"><span>Menu</span></a>
									<div id="menu">
										<ul>
                      <div class="inner">
                        <?php
                          if (($auth->isGuest() == false) && ($auth->isClient() == true)){
                    				echo '<a href="index.php" class="image avatar"><img src="'.$ava.'" alt="" /></a>';
                    				echo '<a href="index.php?page=profile"><h1><strong>'.$name.'</strong></h1></a>';
                          } else if (($auth->isGuest() == false) && ($auth->isWO() == true)){
                    				echo '<a href="index.php?page=profile"><h1><strong>'.$name.'</strong></h1></a>';
                          } else if (($auth->isGuest() == false) && ($auth->isAdmin() == true)){
                    				echo '<a href="#  "><h1><strong>'.$name.'</strong></h1></a>';
                          }
                        ?>
                      </div>
											<li><a href="index.php">Home</a></li>
                      <?php
                        if ($auth->isAdmin() == false){
                          echo '<li><a href="index.php?page=packages">Packages</a></li>';
                        }
                        if ($auth->isClient() == true){
                          echo '<li><a href="index.php?page=wo">Wedding Organizer</a></li>';
          							  //echo '<li><a href="index.php?page=profile">Profile</a></li>';
          							  echo '<li><a href="index.php?page=orders">Orders</a></li>';
                        } else if ($auth->isWO() == true){
                          $ordc = $db->checkWOJobs($id);
                          if ($ordc > 0){
        							         echo '<li><a href="index.php?page=jobs">Jobs <span class="badge">'.$ordc.'</span></a></li>';
                          } else {
        							         echo '<li><a href="index.php?page=jobs">Jobs</a></li>';
                          }
                        } else if ($auth->isAdmin() == true){
                          echo '<li><a href="index.php?page=user">Users</a></li>';
                          echo '<li><a href="index.php?page=ont">Order & Trans</a></li>';
                        }
                      ?>
										</ul>
                    <?php

                      if ($auth->isGuest() == true){
                        echo '<ul>';
                          echo '<h5>Pengguna</h5>';
                          echo '<li><a href="client.php#daftar">Sign Up</a></li>';
                          echo '<li><a href="client.php#masuk">Log In</a></li>';
                        echo '</ul>';
                        echo '<ul>';
                          echo '<h5>Wedding Organizer</h5>';
                          echo '<li><a href="wo.php#daftar">Sign Up</a></li>';
                          echo '<li><a href="wo.php#masuk">Log In</a></li>';
                        echo '</ul>';
                      } else if ($auth->isGuest() == false){
                        echo '<ul class="bottom">';
                          echo '<li><a href="logout.php" class="btn btn-block btn-danger">Keluar</a></li>';
										    echo '</ul>';
                      }

                    ?>
									</div>
								</li>
							</ul>
						</nav>
					</header>

          <!-- panel kanan end -->
        <?php
          if((!count($_GET)) && (!isset($_GET['page']))) {
            //some parameters are set
              include "main_home.php";
          }
        ?>

			</div>

      <?php
      /* PORTALISASI */
      if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        if (isset($_GET['page']) && ($_GET['page']== "wo")){
          if (isset($_GET['user'])){
            //include "error/404.html"; // belum buat
          } else {
            //include "error/404.html"; // belum buat
            include "main_wo.php";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "packages")){
          if  ($auth->isWO() == true){
            include "main_packages.php";
          } else {
            include "main_wo_packages.php";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "jobs")){
          if  ($auth->isWO() == true){
            include "main_jobs.php"; // belum buat
          } else {
            include "error/403.html";
          }

        } else if (isset($_GET['page']) && ($_GET['page'] == "orders")){
          if  ($auth->isClient() == true){
            if (isset($_GET['o'])) {
            // input orderan disini
              $wid = $db->getWoIdByPackageId($sec->input($_GET['o']));
              if ($wid != ""){
                if ($db->storeClientOrder($id,$wid, $sec->input($_GET['o']))){
                  $gb->showInfo("Success","Order package success!");
                  //header('location:./index.php?page=orders')
                  ?>
                  <script type="text/javascript">
                    window.location.href = 'index.php?page=orders';
                  </script>
                  <?php
                  //exit;
                } else {
                  $gb->showInfo("Error","You already ordered this package!");
                }
              } else {
                $gb->showInfo("Error","Wrong order number!");
              }
            } else {
              include "main_order.php";
            }
          } else if (isset($_GET['page']) && ($_GET['page'] == "jobs")){

          } else if  ($auth->isGuest() == true) {
            include "error/404.html";
          } else if  ($auth->isWO() == true) {
            include "error/403.html";
          } else {
            include "error/404.html";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "profile")){
          if ($auth->isGuest() == false) {
            include "main_profile.php";
          } else {
            include "error/404.html";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "upass")){
          if ($auth->isGuest() == false) {
            include "main_passwd.php";
          } else {
            include "error/404.html";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "delp")){
          // wo delete package
          if ($id == $sec->input($_GET['o1'])){
            if ($db->deletePackage($sec->input($_GET['o1']),$sec->input($_GET['o2']),$sec->input($_GET['o3']))){
              $gb->showInfo("Success","Delete package success!");
              //header('location:./index.php?page=packages#paket');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=packages#paket';
              </script>
              <?php
              //exit;
            } else {
              $gb->showInfo("Failed","Delete package failed!");
              //header('location:./index.php?page=packages#paket');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=packages#paket';
              </script>
              <?php
              //exit;
            }
          } else {
            $gb->showInfo("Fail","wrong id!");
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "delf")){
          // wo delete facility
          if ($id == $sec->input($_GET['o1'])){
            if ($db->deleteFacility($sec->input($_GET['o2']),$sec->input($_GET['o3']))){
              $gb->showInfo("Success","Delete facility success!");
              //header('location:./index.php?page=packages#fasilitas');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=packages#fasilitas';
              </script>
              <?php
              //exit;
            } else {
              $gb->showInfo("Failed","Delete facility failed!");
              //header('location:./index.php?page=packages#fasilitas');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=packages#fasilitas';
              </script>
              <?php
              //exit;
            }
          } else {
            $gb->showInfo("Fail","wrong id!");
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "delwo")){
          // admin delete wo
          //if ($id == $sec->input($_GET['o1'])){
            if ($db->deleteWO($sec->input($_GET['o1']),$sec->input($_GET['o2']))){
              $gb->showInfo("Success","Delete wo success!");
              //header('location:./index.php?page=user');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=user';
              </script>
              <?php
              //exit;
            } else {
              $gb->showInfo("Failed","Delete wo failed!");
              //header('location:./index.php?page=user');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=user';
              </script>
              <?php
              //exit;
            }
          //} else {
          //  $gb->showInfo("Fail","wrong id!");
          //}
        } else if (isset($_GET['page']) && ($_GET['page'] == "delcl")){
          // admin delete client
          //if ($id == $sec->input($_GET['o1'])){
            if ($db->deleteClient($sec->input($_GET['o1']),$sec->input($_GET['o2']))){
              $gb->showInfo("Success","Delete client success!");
              //header('location:./index.php?page=user');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=user';
              </script>
              <?php
              //exit;
            } else {
              $gb->showInfo("Failed","Delete client failed!");
              //header('location:./index.php?page=user');
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=user';
              </script>
              <?php
              //exit;
            }
          //} else {
          //  $gb->showInfo("Fail","wrong id!");
          //}
        } else if (isset($_GET['page']) && ($_GET['page'] == "delo")){
          // user cancel order
          if ($auth->isClient()){
            if ($db->deleteOrder($sec->input($_GET['o1']),$sec->input($_GET['o2']))){
              $gb->showInfo("Success","Delete order success!");
              //header('location:./index.php?page=orders')
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=orders';
              </script>
              <?php
              //exit;
            } else {
              $gb->showInfo("Failed","Delete order failed!");
              //header('location:./index.php?page=orders')
              ?>
              <script type="text/javascript">
                window.location.href = 'index.php?page=orders';
              </script>
              <?php
              //exit;
            }
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "user")) {
          if ($auth->isAdmin() == false) {
            include "error/403.html";
          } else {
            include "main_admin_user.php";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "ont")) {
          if ($auth->isAdmin() == false) {
            include "error/403.html";
          } else {
            include "main_admin_ont.php";
          }
        } else if (isset($_GET['page']) && ($_GET['page'] == "rcpass")) {
            if ( (isset($_GET['cn'])) && (isset($_GET['cs'])) ) {
                include_once 'database.php';
                include_once 'security.php';
            
                $db = new Database();
                $sec = new Security();

                $dec1 = $sec->encrypt_decrypt("decrypt", $_GET['cn']);
                //$dec2 = $sec->encrypt_decrypt("decrypt", $_GET['cs']);
                
                if ($_GET['cs'] == "1"){
                    
                  echo '<div id="alert_message" class="alert alert-success senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Password changed !</span>';
                  echo '</div>';
                  
                    include "main_home.php";
                } else if ($_GET['cs'] == "0"){
                  echo '<div id="alert_message" class="alert alert-error senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Password changed failed !</span>';
                  echo '</div>';
                    
                    include "main_home.php";
                } else {
                    include "error/404.html";
                }    
                
            } else {
                include "error/404.html";
            }
        } else if (isset($_GET['page']) && ($_GET['page'] == "rwpass")) {
            if ( (isset($_GET['wn'])) && (isset($_GET['ws'])) ) {
                include_once 'database.php';
                include_once 'security.php';
            
                $db = new Database();
                $sec = new Security();

                $dec1 = $sec->encrypt_decrypt("decrypt", $_GET['wn']);
                //$dec2 = $sec->encrypt_decrypt("decrypt", $_GET['ws']);
                
                if ($_GET['ws'] == "1"){
                    
                  echo '<div id="alert_message" class="alert alert-success senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Password changed !</span>';
                  echo '</div>';
                  
                    include "main_home.php";
                } else if ($_GET['ws'] == "0"){
                  echo '<div id="alert_message" class="alert alert-error senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Password changed failed !</span>';
                  echo '</div>';
                    
                    include "main_home.php";
                } else {
                    include "error/404.html";
                }    
                
            } else {
                include "error/404.html";
            }
        } else if (isset($_GET['page']) && ($_GET['page'] != "")){
          include "error/404.html";
        } else if (isset($_GET['wo']) && ($_GET['wo'])) {
          if ($auth->isGuest() == true) {
            include "error/403.html";
          } else if ($auth->isClient() == true) {
            //include "error/404.html";
            include "main_wo_profile.php";
            // include page wo profile by id
          }
        } else if ( (isset($_GET['cm'])) && (isset($_GET['cw'])) ) {
            include_once 'database.php';
            include_once 'security.php';
            
            $db = new Database();
            $sec = new Security();

            $dec1 = $sec->encrypt_decrypt("decrypt", $_GET['cm']);
            $dec2 = $sec->encrypt_decrypt("decrypt", $_GET['cw']);
            
            if ($dec2 == "client"){
              if ($db->getClientConfirm($dec1) == 0){
                if ($db->setClientConfirm($dec1)){
                  echo '<div id="alert_message" class="alert alert-success senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Email confirmed !</span>';
                  echo '</div>';
                } else {
                  echo '<div id="alert_message" class="alert alert-error senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Something wrong !</span>';
                  echo '</div>';
                }
              } else {
                  echo '<div id="alert_message" class="alert alert-warning senter" style="width:400px;margin-left: auto;margin-right: auto;margin-top:20px">';
                    echo '<span class="close" data-dismiss="alert">×</span>';
                    echo '<span>Email already confirmed !</span>';
                  echo '</div>';
              }
            }
            
            include "main_home.php";
            
        } else {
          //include "error/404.html";
        }
      }
      ?>

      <!-- MESSAGE CHAT -->
      <?php
        if ($auth->isAdmin() == true){
          /*
            echo '<div class="chata_box">';
              echo '<div class="chat_head"> Chat Box</div>';
              echo '<div class="chat_body">';
                echo '<div class="user" id="Administrator"> Administrator</div>';
                echo '<div class="user" id="Administrator2"> Administrator2</div>';
                echo '<div class="user" id="Administrator3"> Administrator3</div>';
              echo '</div>';
            echo '</div>';
          */
            $db->adminGetGuestChatBox();
            $db->adminGetGuestChats();
            $db->adminGetClChats();
            $db->adminGetWOChats();

        } else if ($auth->isClient() == true){
          include_once 'security.php';
          $sec = new Security();

          echo '<div class="box_na msg_box" id="adminc" data-extra="'.$id.'" >';
            echo '<div class="msg_head">Administrator';
                //echo '<div class="close">x</div>';
            echo '</div>';
            echo '<div class="msg_wrap">';
              echo '<div class="msg_body">';
                $db->pullChatCA($id,'admin');
                echo '<div class="msg_push" id="adminc"></div>';
              echo '</div>';
              echo '<div class="msg_footer">';
                echo '<textarea class="msg_input" rows="2" required=""></textarea>';
              echo '</div>';
            echo '</div>';
          echo '</div>';

        } else if ($auth->isWO() == true){
          include_once 'security.php';
          $sec = new Security();

          echo '<div class="msg_box box_na" id="adminw" data-extra="'.$id.'" >';
            echo '<div class="msg_head">Administrator';
              //echo '<div class="close">x</div>';
            echo '</div>';
            echo '<div class="msg_wrap">';
              echo '<div class="msg_body">';
                $db->pullChatWA($id,'admin');
                echo '<div class="msg_push" id="adminw"></div>';
              echo '</div>';
              echo '<div class="msg_footer">';
                echo '<textarea class="msg_input" rows="2" required=""></textarea>';
              echo '</div>';
            echo '</div>';
          echo '</div>';

        } else if ($auth->isGuest() == true){
          include_once 'database.php';
          include_once 'security.php';
          $db = new Database();
          $sec = new Security();

          $enc = $sec->encrypt_decrypt("encrypt", $sec->get_client_ip());
          $dec = $sec->encrypt_decrypt("decrypt", $enc);

            echo '<div class="msg_box box_na" id="adming" data-extra="'.$enc.'" data-extras="'.$sec->get_client_ip().'" >';
              echo '<div class="msg_head">Administrator';
                //echo '<div class="close">x</div>';
              echo '</div>';
              echo '<div class="msg_wrap">';
                echo '<div class="msg_body">';
                  echo '<div class="msg_a">Halo, ada yang bisa kami bantu ?</div>';
                  $db->pullChatGA($dec,'admin');
                  echo '<div class="msg_push" id="adming"></div>';
                echo '</div>';
                echo '<div class="msg_footer">';
                  echo '<textarea class="msg_input" rows="2" required=""></textarea>';
                echo '</div>';
              echo '</div>';
            echo '</div>';
        }
      ?>

      <div class="modal fade" id="mdl_confirm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" style="color:#555">Confirmation</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <p style="color:#555">lol</p>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" id="close-confirm" data-dismiss="modal">No</button>
              <a href="#"><button type="button" class="btn btn-primary" id="submit-confirm" name="submit-addr">Yes</button></a>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <div class="modal fade" id="mdl_info" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#555">Info</h4>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <p style="color:#555">lol</p>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit-confirm" name="submit-addr" data-dismiss="modal">Ok</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
