<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: login.php');
	}
	require('db_connect.php');
	
	function addComment($post_id, $username, $comment) {
		global $db;
		$query = "INSERT INTO comments VALUES (:post_id, :username, :comment)";

		$statement = $db->prepare($query);
		$statement->bindValue(':post_id', $post_id);
		$statement->bindValue(':username', $username);
		$statement->bindValue(':comment', $comment);
		$statement->execute();
		$statement->closeCursor();
	}

	if(isset($_POST['comment_btn'])) {
		if(!empty(trim($_POST['comment_box']))){
			$comment_contents = htmlspecialchars(trim($_POST['comment_box']));
			$post_id = htmlspecialchars(trim($_POST['hidden_post_id']));
			echo "comment: " . $comment_contents;
			echo " post id: " . $post_id;
			addComment($post_id, $_SESSION['user'], $comment_contents);
		}
		// else {
		// 	$_POST['err_read'] = "Error: missing field(s)";
		// }
	}

	$filter_query = $filter_statement = "";
	if(!isset($_GET['genre']) || $_GET['genre'] == 'all') {
		$filter_query = "SELECT * FROM post";
		$filter_statement = $db->prepare($filter_query);
	}
	else {
		$filter_query = "SELECT * FROM post WHERE book_genre = :genre";
		$filter_statement = $db->prepare($filter_query);
		$filter_statement->bindValue(':genre', $_GET['genre']);
	}

	$filter_statement->execute();
	$post_list = $filter_statement->fetchAll();
	$filter_statement->closeCursor();

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

			var item1 = document.createElement("div"); // img 
			item1.className = "item1";
			 img = document.createElement("img");
			img.src = "educated.jpg";
			img.style = "width:100px;height:165px";
			item1.appendChild(img);

			var item2 = document.createElement("div"); // username reviewed title by author
			item2.className = "item2";
			var line1 = document.createElement("p");
			var username = JSON.parse(JSON.stringify(param[1])); 
			var title = JSON.parse(JSON.stringify(param[2]));
			var author = JSON.parse(JSON.stringify(param[3]));
			line1.innerHTML = "<b>" + username + "</b>  reviewed  " + "<b>" + title + "</b>" + "  by  " + "<b>" + author + "</b>";
			item2.appendChild(line1);

			// var genre = JSON.stringify(param[3]);
			// var line2 = document.createElement("p");
			// line2.innerHTML = genre;
			// item3.appendChild(line2);

			var item3 = document.createElement("div"); // rating +genre
			item3.className = "item3";
			var genre = JSON.parse(JSON.stringify(param[4]));
			var rating = JSON.parse(JSON.stringify(param[5]));
			var line3 = document.createElement("p");
			line3.innerHTML = rating + "/5" + "<i class='fas fa-star'></i>   ||   " + genre;
			item3.appendChild(line3);

			var item4 = document.createElement("div"); // description
			item4.className = "item4";
			var description = JSON.parse(JSON.stringify(param[6]));
			var line4 = document.createElement("p");
			line4.innerHTML = "<i>" + description + "</i>";
			item4.appendChild(line4);

			// document.write(JSON.stringify(param));
			
			// COMMENT FORM
			var comment_form = document.createElement("form");
			// comment_form.action = "explore.php";
			comment_form.name = "comment_form";
			comment_form.method = "post";

			var item5 = document.createElement("div");
			item5.className = "item5";
			var comment = document.createElement("input");
			comment.placeholder = "type a comment...";
			comment.name = "comment_box";
			comment.id = "comment_box";

			var commentBtn = document.createElement("button");
			var post_id = JSON.parse(JSON.stringify(param[0]));
			var hidden_post_id = document.createElement("input");
			hidden_post_id.type = "hidden";
			hidden_post_id.name = "hidden_post_id";
			hidden_post_id.value = post_id;
			commentBtn.type = "submit";
			commentBtn.textContent = "post"
			commentBtn.class = "btn btn-primary";
			commentBtn.className = "btn btn-outline-info";
			commentBtn.name = "comment_btn";
			commentBtn.id = "comment_btn";

			// var like = document.createElement("i");
			// like.className = "far fa-heart";

			// comment.appendChild(commentBtn);
			item5.appendChild(comment);
			item5.appendChild(hidden_post_id);
			item5.appendChild(commentBtn);
			comment_form.appendChild(item5);
			// item5.appendChild(like);

			postContainer.appendChild(item1);
			postContainer.appendChild(item2);
			postContainer.appendChild(item3);
			postContainer.appendChild(item4);
			postContainer.appendChild(comment_form);
			// postContainer.appendChild(item5);
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