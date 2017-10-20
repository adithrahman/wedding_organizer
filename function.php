<?php

class Globalz{
  function is_image($path){
    $a = getimagesize($path);
    $image_type = $a[2];

    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
  }

  function upload_file($file){

  }

  function showInfo($title,$msg){
      echo "<script type='text/javascript'>";
        echo "$(window).load(function(){ ";
          echo "$('#mdl_info .modal-title').text('".$title."');";
          $msgx=implode("/(\r\n|\n|\r)/gm",$msg);
          echo "var msg='".$msgx."';";
          echo "$('#mdl_info .modal-body p').text(msg);";
          echo "$('#mdl_info').modal('show');";
      echo "});</script>";
  }
}

?>
