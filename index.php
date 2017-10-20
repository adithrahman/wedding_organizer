
<?php
	session_start();
	include_once "auth.php";
  $auth = new auth();

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Menikah Syar'i</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<link rel="icon" href="favicon.png" type="image/x-icon" />

		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/jquery.bxslider.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/dataTables.bootstrap.min.css" />

		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/dataTables.bootstrap.min.js"></script>

	</head>



		<body class="landing is-loading-0 is-loading-1 is-loading-2">

			<?php
				include "main/main.php";
			?>

				    <!-- Scripts -->
					<script src="assets/js/jquery.bxslider.min.js"></script>
					<script src="assets/js/jquery.scrollex.min.js"></script>
					<script src="assets/js/jquery.scrolly.min.js"></script>
					<script src="assets/js/jquery.jeditable.js"></script>
					<script src="assets/js/skel.min.js"></script>
					<script src="assets/js/util.js"></script>
					<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
					<script src="assets/js/main.js"></script>

		</body>
</html>
