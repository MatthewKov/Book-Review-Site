<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->

<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header('Location: login.php');
	}
	require('db_connect.php');

	function addBookToList($list, $title, $author, $user) {
		if(bookExists($title, $author, $user, $list)) {
			if($list == "books_read")
				$_POST['err_read'] = "This list already contains that book";
			else
				$_POST['err_to_read'] = "This list already contains that book";
		}
		else {
			global $db;

			$query = "";
			if($list == "books_read") // $list has the same name as the table
				$query = "INSERT INTO books_read VALUES (:user, :title, :author)";
			else
				$query = "INSERT INTO books_to_read VALUES (:user, :title, :author)";

			$statement = $db->prepare($query);
			$statement->bindValue(':user', $user);
			$statement->bindValue(':title', $title);
			$statement->bindValue(':author', $author);
			$statement->execute();
			$statement->closeCursor();
		}
	}

	function bookExists($title, $author, $user, $list) {
		global $db;

		$query = "";
		if($list == "books_read")
			$query = "SELECT * FROM books_read WHERE username=:user AND title=:title AND author=:author";
		else
			$query = "SELECT * FROM books_to_read WHERE username=:user AND title=:title AND author=:author";

		$statement = $db->prepare($query);
		$statement->bindValue(':user', $user);
		$statement->bindValue(':title', $title);
		$statement->bindValue(':author', $author);
		$statement->execute();

		$result = $statement->fetchAll();

		if(empty($result)) { //query returned nothing, so book does not exist
			$statement->closeCursor();
			return false;
		}

		// Membership in the list should be case insensitive
		$userBool = strtolower($result[0]['username']) == strtolower($user);
		$titleBool = strtolower($result[0]['title']) == strtolower($title);
		$authorBool = strtolower($result[0]['author']) == strtolower($author);
		if($userBool && $titleBool && $authorBool) {
			$statement->closeCursor();
			return true;
		}
		$statement->closeCursor();
		return false;
	}

	function deleteBook($title, $author, $user, $list) {
		global $db;

		$query = "";
		if($list == "books_read")
			$query = "DELETE FROM books_read WHERE username=:user AND title=:title AND author=:author";
		else
			$query = "DELETE FROM books_to_read WHERE username=:user AND title=:title AND author=:author";

		$statement = $db->prepare($query);
		$statement->bindValue(':user', $user);
		$statement->bindValue(':title', $title);
		$statement->bindValue(':author', $author);
		$statement->execute();
		$statement->closeCursor();
	}

	function updateBio($user, $content) {
		global $db;

		$query = "UPDATE user SET bio = :content WHERE username = :user";

		$statement = $db->prepare($query);
		$statement->bindValue(':content', $content);
		$statement->bindValue(':user', $user);
		$statement->execute();
		$statement->closeCursor();
	}

	$_POST['err_read'] = "";
	$_POST['err_to_read'] = "";

	if(isset($_POST['add_read'])) {
		if(!empty(trim($_POST['title_read'])) && !empty(trim($_POST['author_read']))) {
			$title = htmlspecialchars(trim($_POST['title_read']));
			$author = htmlspecialchars(trim($_POST['author_read']));
			addBookToList("books_read", $title, $author, $_SESSION['user']);
		}
		else {
			$_POST['err_read'] = "Error: missing field(s)";
		}
	}
	else if(isset($_POST['add_to_read'])) {
		if(!empty(trim($_POST['title_to_read'])) && !empty(trim($_POST['author_to_read']))) {
			$title = htmlspecialchars(trim($_POST['title_to_read']));
			$author = htmlspecialchars(trim($_POST['author_to_read']));
			addBookToList("books_to_read", $title, $author, $_SESSION['user']);
		}
		else {
			$_POST['err_to_read'] = "Error: missing field(s)";
		}
	}

	if(isset($_POST['read_remove'])) {
		$data = explode("_", $_POST['read_remove']);
		$title = $data[1];
		$author = $data[2];
		deleteBook($title, $author, $_SESSION['user'], "books_read");
	}
	else if(isset($_POST['to_read_remove'])) {
		$data = explode("_", $_POST['to_read_remove']);
		$title = $data[2];
		$author = $data[3];
		deleteBook($title, $author, $_SESSION['user'], "books_to_read");
	}

	if(isset($_POST['update_bio'])) {
		updateBio($_SESSION['user'], trim($_POST['bio_hidden']));
	}

	global $db;

	$profile_query = "SELECT profile_pic, bio FROM user WHERE username = :usr";
	$profile_statement = $db->prepare($profile_query);
	$profile_statement->bindValue(':usr', $_SESSION['user']);
	$profile_statement->execute();
	$profile_list = $profile_statement->fetch();
	$profile_statement->closeCursor();

	$books_read_query = "SELECT * FROM books_read WHERE username = :usr";
	$read_statement = $db->prepare($books_read_query);
	$read_statement->bindValue(':usr', $_SESSION['user']);
	$read_statement->execute();
	$books_read_list = $read_statement->fetchAll();
	$read_statement->closeCursor();

	$books_to_read_query = "SELECT * FROM books_to_read WHERE username = :usr";
	$to_read_statement = $db->prepare($books_to_read_query);
	$to_read_statement->bindValue(':usr', $_SESSION['user']);
	$to_read_statement->execute();
	$books_to_read_list = $to_read_statement->fetchAll();
	$to_read_statement->closeCursor();

?>

<html>
	<head>
		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<title>BookKeeper</title>

  		
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />
  		<script src="https://kit.fontawesome.com/595acf0be0.js" crossorigin="anonymous"></script>
  		<link rel="stylesheet" href="prof-style.css">

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
		
		<div id="person-info">
			<form action="profile.php" name="bio_form" method="post">
				<div id=user>
					<img id="profile-pic" src="profile-pic.png" alt="" width="100" height="100">
					<label for="profile-pic"><?php echo $_SESSION['user'] ?></label>
				</div>
				<div id="bio" contenteditable="true" onkeyup="updateBio()"><?php echo $profile_list['bio']; ?></div>
				<div>
					<!-- The ajax link is preventing this button from centering -->
					<button type="submit" name="update_bio" class="btn btn-secondary center">Update</button>
					<input type="hidden" id="bio_hidden" name="bio_hidden">
				</div>
			</form>
		</div>

		<div id="bookshelf" class="flex-container">
			<div class="flex-child">
				<form action="profile.php" name="read_form" method="post">
					<h4>Books I've read</h4>
					<ul id="books_read">
						<?php
							foreach($books_read_list as $book) {
								$name = "read_" . $book['title'] . "_" . $book['author'];
								echo '<li>' . $book['title'] . ' by ' . $book['author'] .
									'<button type="submit" name="read_remove" value="' . $name . '" class="btn btn-secondary">Remove</button>
									</li>';
							}
						?>
					</ul>
					<input id="title_read" name="title_read" type="text" placeholder="Add a book"> & <input id="author_read" name="author_read" type="text" placeholder="Add author">
					<button id="add_read" name="add_read" class="btn btn-primary" type="submit">Add to list</button>
					<div><span class="error_message" id="err_read"><?php echo $_POST['err_read'] ?></span></div>
				</form>
			</div>
			<div class="flex-child">
				<h4>Books I want to read</h4>
				<form action="profile.php" method="post">
					<ul id="books_to_read">
						<?php
							foreach($books_to_read_list as $book) {
								$name = "to_read_" . $book['title'] . "_" . $book['author'];
								echo '<li>' . $book['title'] . ' by ' . $book['author'] .
									'<button type="submit" name="to_read_remove" value="' . $name . '" class="btn btn-secondary">Remove</button>
									</li>';
							}
						?>
					</ul>
				</form>
				<form action="profile.php" name="to_read_form" method="post">
					<input id="title_to_read" name="title_to_read" type="text" placeholder="Add a book"> & <input id="author_to_read" name="author_to_read" type="text" placeholder="Add author">
					<button id="add_to_read" name="add_to_read" class="btn btn-primary" type="submit">Add to list</button>
					<div><span class="error_message" id="err_to_read"><?php echo $_POST['err_to_read'] ?></span></div>
				</form>
			</div>
		</div>

		
		<script>
	  		$(function(){
  				$("#nav-placeholder").load("navbar.php");
			});

			function updateBio() {
				//bug clears bio when hit without changing anything
				var bio = document.getElementById('bio').innerHTML;
				var hidden = document.getElementById('bio_hidden');
				hidden.value = bio;
		    }
  		</script>
	</body>
		
</html>