<?php

session_start();
$_SESSION['visitor'] = "";

// memulai session agar dapat menggunakan variabel $_SESSION

// mengosongkan data yang tersimpan di dalam variabel $_SESSION
$_SESSION = array();

// mengakhiri session
session_destroy();
session_start();
unset($_SESSION['visitor']);

//header('location:./');

    //header('location:./index.php?page=upass');
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php';
    </script>
