<?php

//session_start();
  if ( (!isset($_SESSION['visitor'])) || empty($_SESSION['visitor']) ) $_SESSION['visitor'] = "guest";

class auth{


  function isGuest(){
	  if (($_SESSION['visitor'] != "admin") and ($_SESSION['visitor'] != "client") and ($_SESSION['visitor'] != "wo")) {
		  return true;
    }
    else return false;
  }

  function isAdmin(){
	  if ($_SESSION['visitor'] == "admin") return true;
	  else return false;
  }

  function isClient(){
	  if ($_SESSION['visitor'] == "client") return true;
	  else return false;
  }

  function isWO(){
	  if ($_SESSION['visitor'] == "wo") return true;
	  else return false;
  }
//} else {
//  $_SESSION['visitor'] == "";
//}

}
?>
