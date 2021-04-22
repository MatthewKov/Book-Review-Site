<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: login.php');
	}
?>

<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" href="navbar-style.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fffbd6; height: 100px;">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="explore.php"><i class="fas fa-book-reader fa-3x" style="color:#53917E;"></i></a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
	        <li class="nav-item">
	          <a class="nav-link" href="explore.php" aria-current="page" style="font-size: 20px;">explore</a>
	        </li>
	         <li class="nav-item">
	          <a id="nav-link" class="nav-link" href="write_review.php" style="font-size: 20px;">write a review</a>
	        </li>
	    	<li class="nav-item dropdown">
	          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 20px;">
	            my profile
	          </a>
	          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	            <li><a class="dropdown-item" href="profile.php">my posts</a></li>
	            <li><hr class="dropdown-divider"></li>
	            <li><a class="dropdown-item" href="profile.php">my bookshelf</a></li>
	          </ul>
	        </li>
	      </ul>
	      <form action="logout.php" method="get">
        	<input type="submit" value="Log out" class="btn btn-dark" />
      	  </form>
	    </div>
	  </div>
	</nav>
</body>
<!--Popper-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
</html>