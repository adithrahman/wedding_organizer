
		<!-- Main -->
			<div id="m-thumb">

				<!-- Header -->
					<header id="h-thumb">
						<h1>Wedding Oganizer</h1>
						<p>Wedding organizer menyiapkan paket sesuai dengan kebutuhan anda</a></p>
						<input type="text" id="search" name="search" placeholder="Search..."/>
					</header>

				<!-- Thumbnail -->
				<div id="result">
					<?php
						require_once 'database.php';
						$db = new Database();
						require_once 'security.php';
						$sec = new Security();
						if (isset($_GET['search'])){
						    $data = $db->getWODetailsByQuery($sec->input($_GET['search']));
						} else {
						    $data = $db->getWODetails();
						}
					?>
				</div>

      </div>

      <link rel="stylesheet" href="assets/css/main_wo.css" />
		  <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
<script>
$(document).ready(function(){
   function search(){
 
      var title=$("#search").val();
 
        if(title!=""){
            //$("#result").html("<img alt="ajax search" src='ajax-loader.gif'/>");
            $.ajax({
                type:"post",
                url:"search.php",
                data:"key="+title,
                success:function(data){
                    $("#result").html(data);
                    $("#search").val("");
                }
            });
        }
    }
  
    $('#search').keyup(function(e) {
      if(e.keyCode == 13) {
        //search();
        var q=$("#search").val();
        window.location.replace("index.php?page=wo&search="+q);
      }
    });
});
</script>