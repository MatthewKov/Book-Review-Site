<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: login.php');
	}
	require('db_connect.php');
	
	global $db;

	$query = $statement = "";
	if(!isset($_GET['genre']) || $_GET['genre'] == 'all') {
		$query = "SELECT * FROM post";
		$statement = $db->prepare($query);
	}
	else {
		$query = "SELECT * FROM post WHERE book_genre = :genre";
		$statement = $db->prepare($query);
		$statement->bindValue(':genre', $_GET['genre']);
	}
	
	
	$statement->execute();
	$post_list = $statement->fetchAll();
	$statement->closeCursor();
?>

<html>
	<head>
		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<title>Website name</title>
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  		<script src="https://kit.fontawesome.com/595acf0be0.js" crossorigin="anonymous"></script>
  		<link rel="stylesheet" href="explore-style.css">
	</head>
	<body>
		<header>
			<div id="nav-placeholder"></div>
		</header>
		<script>
	  		$(function(){
  				$("#nav-placeholder").load("navbar.php");
			});
  		</script>
		<div id="filtering">
			<div class="wrapper">
				<input id="search-bar" type="text" placeholder="Search for a book...">
				<button class="btn" onclick="search()"><i class="fa fa-search" id="search-icon"></i></button><br>
				<div><span class="err_search" id="err_search"></span></div>
			</div>
			<p>Or filter posts by genre:</p>
  			<form action="explore.php" method="get">
  				<label class="radio-inline">
      				<input type="radio" name="genre" value="all" <?php if(!isset($_GET['genre']) || $_GET['genre'] == 'all') echo 'checked' ?> >all
    			</label>
    			<label class="radio-inline">
      				<input type="radio" name="genre" value="classic" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'classic') echo 'checked' ?> >classic
    			</label>
    			<label class="radio-inline">
					<input type="radio" name="genre" value="fantasy" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'fantasy') echo 'checked' ?>>fantasy
				</label>
    			<label class="radio-inline">
      				<input type="radio" name="genre" value="horror" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'horror') echo 'checked' ?>>horror
    			</label>
    			<label class="radio-inline">
					<input type="radio" name="genre" value="memoir" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'memoir') echo 'checked' ?>>memoir
				</label>
				<label class="radio-inline">
					<input type="radio" name="genre" value="mystery" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'mystery') echo 'checked' ?>>mystery
				</label>
				<label class="radio-inline">
					<input type="radio" name="genre" value="nonfiction" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'nonfiction') echo 'checked' ?>>nonfiction
				</label>
				<label class="radio-inline">
					<input type="radio" name="genre" value="romance" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'romance') echo 'checked' ?>>romance
				</label>
				<label class="radio-inline">
					<input type="radio" name="genre" value="sci-fi" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'sci-fi') echo 'checked' ?>>sci-fi
				</label>
				<input type="submit" name="submit" value="Filter" class="btn btn-primary" id="filter-btn"/>
  			</form>
		</div>
		<?php
			foreach($post_list as $post) {
				echo "<p>" . $post['book_title'] . " by " . $post['book_author'] . "</p>";
			}
		?>
		<div id="outer-shell">
		<!-- First Hardcoded Post -->
		<div class="grid-container">
		  <div class="post-container">
		  	<div class="item1"><img src="educated.jpg" alt="Educated" style="width:100px;height:165px;"></div>	
  			<div class="item2"><b>louisaevola100</b> reviewed <b>Educated</b> by <b>Tara Westover</b></div>	
  			<div class="item3">
  				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
  			</div>
  			<div class="item4">Enthralling book about a peculiar childhood! A must read memoir.</div>
  			<div class="item5">
  				<input id="reaction" type="text" placeholder="type a comment..."><button type="button" id="submit" class="btn btn-outline-info">Submit  
  				</button></input>
  				<br/>
  				<i id="like" class="far fa-heart"></i>
  			</div>
		  </div>

		  <!-- Second Hardcoded Post -->
		  <div class="grid-container">
		  <div class="post-container">
		  	<div class="item1"><img src="light-book.jpg" alt="All the Light We Cannot See" style="width:100px;height:165px;"></div>	
  			<div class="item2"><b>matthewkovalenko</b> reviewed <b>All the Light We Cannot See</b> by <b>Anthony Doerr</b></div>	
  			<div class="item3">
  				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star-half-alt"></span>
  			</div>
  			<div class="item4">Really well written book that weaves fiction and history.</div>
  			<div class="item5">
  				<input id="reaction" type="text" placeholder="type a comment..."><button type="button" id="submit" class="btn btn-outline-info">Submit  
  				</button></input>
  				<br/>
  				<i id="like" class="far fa-heart"></i>
  			</div>
		  </div>

		  <!-- Third Hardcoded Post -->
		  <div class="grid-container">
		  <div class="post-container">
		  	<div class="item1"><img src="in-the-time.jpg" alt="In the Time of the Butterflies" style="width:100px;height:165px;"></div>	
  			<div class="item2"><b>user4578475</b> reviewed <b>In the Time of the Butterflies</b> by <b>Julia Alvarez</b></div>	
  			<div class="item3">
  				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
  			</div>
  			<div class="item4">An amazing book about sisterhood and family inspired by true events.</div>
  			<div class="item5">
  				<input id="reaction" type="text" placeholder="type a comment..."><button type="button" id="submit" class="btn btn-outline-info">Submit  
  				</button></input>
  				<br/>
  				<i id="like" class="far fa-heart"></i>
  			</div>
		  </div>
		</div>

		<!-- Fourth Hardcoded Post -->
		<div class="grid-container">
		  <div class="post-container">
		  	<div class="item1"><img src="crazy-rich-asians.jpg" alt="Crazy Rich Asians" style="width:100px;height:165px;"></div>	
  			<div class="item2"><b>newuser</b> reviewed <b>Crazy Rich Asians</b> by <b>Kevin Kwan</b></div>	
  			<div class="item3">
  				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star checked"></span>
				<span class="fa fa-star"></span>
				<span class="far fa-star"></span>
  			</div>
  			<div class="item4">Better than the movie, which is saying a lot!</div>
  			<div class="item5">
  				<input id="reaction" type="text" placeholder="type a comment..."><button type="button" id="submit" class="btn btn-outline-info">Submit  
  				</button></input>
  				<br/>
  				<i id="like" class="far fa-heart"></i>
  			</div>
		  </div>
		</div>
	</div>
	<script>
		let search = function() {
			var searchBar = document.getElementById("search-bar").value;
			console.log(searchBar);
			if(searchBar == "") {
				document.getElementById("err_search").innerHTML = "please enter a title or author to search";
			}
			else {
				document.getElementById("err_search").innerHTML = "";	
			}
		}	
	</script>
	</body>
</html>