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
  		<title>BookKeeper</title>
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
		
			<div class="wrapper">
				<input id="search-bar" type="text" placeholder="Search for a book...">
				<button class="btn" onclick="search()" style="background-color: #53917E"><i class="fa fa-search" id="search-icon" style="color:white"></i></button><br>
				<div><span class="err_search" id="err_search"></span></div>
			</div>

			<div id="filtering">
				<p>or filter posts by genre:</p>
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
					<input type="submit" name="submit" id="submit" value="Filter" class="btn btn-primary" id="filter-btn"/>
  				</form>
			</div>
		
		<div id='shell'></div>
		<script>
	
	function test(param){
			var shell = document.getElementById("shell");
		
			var gridContainer = document.createElement("div");
			gridContainer.className = "grid-container"; 
			shell.appendChild(gridContainer);
			
			var postContainer = document.createElement("div");
			postContainer.className = "post-container";
			gridContainer.appendChild(postContainer);


			var item0 = document.createElement("div"); // username
			item0.className = "item0";
			var line0 = document.createElement("p");
			var username = JSON.stringify(param[1]);
			line0.innerHTML = "<b>" + username + "</b>";
			item0.appendChild(line0);

			var item1 = document.createElement("div"); // img 
			item1.className = "item1";
			 img = document.createElement("img");
			img.src = "educated.jpg";
			img.style = "width:100px;height:165px";
			item1.appendChild(img);

			var item2 = document.createElement("div"); // title by author
			item2.className = "item2";
			var line1 = document.createElement("p");
			var title = JSON.stringify(param[2]);
			var author = JSON.stringify(param[3]);
			line1.innerHTML = "<b>" + title + "</b>" + " by " + author;
			item2.appendChild(line1);

			// var genre = JSON.stringify(param[3]);
			// var line2 = document.createElement("p");
			// line2.innerHTML = genre;
			// item3.appendChild(line2);

			var item3 = document.createElement("div"); // rating
			item3.className = "item3";
			var rating = JSON.stringify(param[5]);
			var line3 = document.createElement("p");
			line3.innerHTML = rating;
			item3.appendChild(line3);

			var item4 = document.createElement("div");
			item4.className = "item4";
			var description = JSON.stringify(param[6]);
			var line4 = document.createElement("p");
			line4.innerHTML = description;
			item4.appendChild(line4);

			// document.write(JSON.stringify(param));
			
			var item5 = document.createElement("div");
			item5.className = "item5";
			var comment = document.createElement("input");
			comment.placeholder = "type a comment...";
			var commentBtn = document.createElement("button");
			commentBtn.type = "button";
			commentBtn.id = "submit";
			commentBtn.className = "btn btn-outline-info";
			var like = document.createElement("i");
			like.className = "far fa-heart";

			comment.appendChild(commentBtn);
			item5.appendChild(comment);
			item5.appendChild(like);

			postContainer.appendChild(item0);
			postContainer.appendChild(item1);
			postContainer.appendChild(item2);
			postContainer.appendChild(item3);
			postContainer.appendChild(item4);
			postContainer.appendChild(item5);
		}	
</script>
		<?php
			foreach($post_list as $post) {
				echo '<script type="text/javascript">
						test(' . json_encode($post) . ');
					   </script>';
			}	
		?>
		
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