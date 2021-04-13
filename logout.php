<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  
  <title>Website name</title>    
</head>
<body>

<?php session_start(); ?>

<!--   <div class="container">
    <h1>CS4640 Survey</h1>
    Successfully logged out 
  </div> -->

<?php
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}

if(count($_SESSION) > 0) {
	foreach($_SESSION as $k => $v) {
		unset($_SESSION[$k]); //remove the key-value pair from session object on server
	}
	session_destroy(); //completely remove the instance on server

	//remove on client side
	setcookie("PHPSESSID", "", time()-3600, "/"); // the "/" is the path to PHPSESSID
  header('Location: login.php');
}
?>

</body>
</html>