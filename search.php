<?php

/**
 * Database config variables
 */
define("DB_HOST", "localhost");
define("DB_USER", "sibershi_wo");
define("DB_PASSWORD", "sibershieldwo1!");
define("DB_DATABASE", "sibershi_wo");

    include_once "security.php";
    $sec = new Security();

    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    
    if(mysqli_connect_errno())
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        
    
      $key = $sec->input($_POST['key']);
      $sql = "SELECT * FROM wo where name LIKE '%$key%'";

      $result=mysqli_query($con,$sql);
          //$stmt->close();

            // check for successful get
            if ($result) {
                /* Get the result */
                //$result = $stmt->get_result();
                //$stmt->close;
			    echo '<section id="thumbnails">';
                while($data=mysqli_fetch_array($result, MYSQLI_ASSOC)){
                //while ($data = $result->fetch_assoc()){
                    echo '<article>';
                      if (file_exists('images/banner/banner_'.$data["wid"].'.jpg') )
                        echo '<a class="thumbnail" href="images/banner/banner_'.$data["wid"].'.jpg" data-position="left center"><img src="images/banner/banner_'.$data["wid"].'.jpg" alt="NO BANNER YET" /></a>';
                      else
                        echo '<a class="thumbnail" href="assets/img/thumbs/01.jpg" data-position="left center"><img src="assets/img/thumbs/01.jpg" alt="BANNER" /></a>';

                      echo '<h2><a href="index.php?wo='.$data["wid"].'">'.$data["wname"].'</a></h2>';

                      echo '<p>';
                        echo $data["wdesc"];
                      echo '</p>';
                    echo '</article>';
                }
                echo '</section>';
                //return $stmt->get_result()->fetch_assoc();
            } else {
                //echo $stmt->error."</br>";
                echo "error";
            }
            
        // Free result set
        mysqli_free_result($result);

    mysqli_close($con);
?>