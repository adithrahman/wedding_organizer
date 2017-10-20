<?php

  require_once 'database.php';
  require_once 'security.php';

  $db = new Database();
  $sec = new Security();

  $idw = $sec->input($_GET["wo"]);
  if ($idw == ""){
    include "error/404.html";
  } else if ($db->isWOExistedByID($idw) == false){
    include "error/404.html";
  } else {
?>

<!-- Header -->
  <div id="lpanel">
    <div class="inner">
      <!--a href="#" class="image avatar"><img src="images/avatar.jpg" alt="" /></a-->
      <h1><strong><?php echo $db->getWONameByID($idw); ?></strong>,<br />
          <?php echo $db->getWODeskripsiByID($idw);?>
      </h1>
    </div>
  </div>

		<!-- Main -->
			<div id="main">

				<!-- One -->
					<section id="one">
						<header class="major">
							<h2>Paket tersedia</h2>
						</header>
            <div class="row">
            	<?php
            	    $data = $db->getWOPackageDetails2($idw);
            	?>
            </div>
            <!--
            <ul class="actions">
							<li><a href="#" class="button">Learn More</a></li>
						</ul>
          -->
					</section>

				<!-- Two -->
					<section id="two">
						<h2>Recent Work</h2>
						<div class="row">
            	<?php
            	    $data = $db->getWOOrderDetails($idw);
            	?>
						</div>
					</section>

				<!-- Three -->
        <?php
        if ($auth->isWO()== false){
        ?>
					<section id="three">
        <!--
						<h2>Kirim pesan</h2>
						<p>Kirim pesan kepada admin WO.</p>
						<div class="row">
            <div class="8u 12u$(small)">
              <form method="post" action="messages.php">
									<div class="row uniform 50%">
										<div class="6u 12u$(xsmall)"><input type="text" name="name" id="name" placeholder="Name" /></div>
										<div class="6u$ 12u$(xsmall)"><input type="email" name="email" id="email" placeholder="Email" /></div>
										<div class="12u$"><textarea name="message" id="message" placeholder="Message" rows="4" style="resize:none;"></textarea></div>
									</div>
								<ul class="actions">
									<li><input type="submit" name="submit-wo" value="Send Message" /></li>
								</ul>
              </form>
            </div
            -->
							<div class="4u$ 12u$(small)">
								<ul class="labeled-icons">
									<li>
										<h3 class="icon fa-home"><span class="label">Address</span></h3>
										<?php echo $db->getWOAddressByID($idw);?>
									</li>
									<li>
										<h3 class="icon fa-mobile"><span class="label">Phone</span></h3>
										<?php echo $db->getWOPhoneByID($idw);?>
									</li>
									<li>
										<h3 class="icon fa-envelope-o"><span class="label">Email</span></h3>
										<a href="#"><?php echo $db->getWOEmailByID($idw);?></a>
									</li>
								</ul>
							</div>
						</div>
					</section>
        <?php
        }
        ?>

			</div>


      <link rel="stylesheet" href="assets/css/main_wo_profile.css" />

<?php
  }
?>
