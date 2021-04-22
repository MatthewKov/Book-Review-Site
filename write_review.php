<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
<?php
session_start();
if(!isset($_SESSION['user'])) {
  header('Location: login.php');
}
require('db_connect.php');

$booktitle = $bookauthor = $rating = $description = $genre = NULL;
$title_msg = $author_msg = $rating_msg = $description_msg =  $genre_msg = NULL;

function addPost($booktitle, $bookauthor, $genre, $rating, $description) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    global $db;
  
    $query = "INSERT INTO post (book_title, book_author, book_genre, rating, description) VALUES(:title, :author, :genre, :rating, :descr)";

    $statement = $db->prepare($query);
    $statement->bindValue(':title', $booktitle);
    $statement->bindValue(':author', $bookauthor);
    $statement->bindValue(':genre', $genre);
    $statement->bindValue(':rating', $rating);
    $statement->bindValue(':descr', $description);
    $statement->execute();        // run query, if the statement is successfully executed, execute() returns true
                                // false otherwise
  
    $statement->closeCursor();    // release hold on this connection
  }
}
if (!empty($_POST['title']) && !empty($_POST['author']) && !empty($_POST['rating']) && !empty($_POST['description']) && !empty($_POST['genre'])){
  $booktitle = trim($_POST['title']);
  $bookauthor = trim($_POST['author']);
  $rating = trim($_POST['rating']);
  $description = trim($_POST['description']);
  echo $_POST['genre'] . "<br/>";
  $genre = trim($_POST['genre']);
  addPost($booktitle, $bookauthor, $genre, $rating, $description);
  echo "<hr/>";
  echo "Your book review submission was successful! <br>";
  echo "We can't wait to share your review of $bookauthor's $booktitle with our community at bookkeeper! <br>";
  echo "Your rating of $booktitle: $rating <br>";
  echo "Your description of $booktitle: $description <br>";	
  echo "Book genre: $genre";
}
else {
  $_POST['err_user'] = "Please fill out all required fields to make a post";
}

?>
<!DOCTYPE html>
<html>
<style>
.header {grid-area: title;}
.item1 { grid-area: book-image; }
.item2 { grid-area: book-title; }
.item3 { grid-area: book-author; }
.item4 { grid-area: rating; }
.item5 { grid-area: genre; }
.item6 { grid-area: description; }

.grid-container {
  display: grid;
  grid-template-areas:
    'title title title title title title'
    'book-image book-title book-title book-title book-title book-title'
    'book-image book-author book-author book-author book-author book-author'
    'book-image rating rating rating rating rating'
    'book-image genre genre genre genre genre'
    'description description description description description description';
  grid-gap: 10px;
  /*background-color: #2196F3;*/
  padding: 15px;
  border: solid black 2px;
  margin-left:100px;
  margin-right:100px;
  margin-top:20px;
  margin-bottom: 40px;
}

.grid-container > div {
  /*background-color: rgba(255, 255, 255, 0.8);*/
  font-size: 20px;
}

#book-img-upload{
  border: solid black 2px;
  padding-top:25px;
}

#outer-shell{
  padding-left:10px;
}

#publish-btn{
  margin-left:650px;
}

#description {
  height:150px;
}

.error_message {
  color: crimson;
  font-style: italic;
  padding: 10px;
}
</style>
<head>
	<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<title>Website name</title>
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  		<script src="https://kit.fontawesome.com/595acf0be0.js" crossorigin="anonymous"></script>
      <!-- <link rel="stylesheet" href="write-review-style.css"> -->
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
  
  <br>
  
  <div class="container" style="padding-left:10px;">
    <!-- Jumbotron -->
    <div class="jumbotron" style="background-color: #CBE2DB; border-radius: 6px">
      <h1 class="display-6" style="padding: 20px 20px 20px 20px; margin:auto; text-align: center">review your latest read!</h1>
    </div>
  </div>
  
  <div class="outer-shell">
  <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<div class="grid-container">		
				<div class="item1" id="book-img-upload"><i class="far fa-images fa-2x"></i><br/><input type="file" id="img" name="img" accept="image/*"></div>	
				<div class="item2" id="book-title">
					<label>Book title: </label>
					<input type="text" name="title" id="title" autofocus />	
				</div>
				<div class="item3" id="book-author">
						<label>Book author: </label>
						<input type="text" name="author" id="author" autofocus />
                </div>
                
                <div class="item4">
                    <div class="rate"
					    data-rateyo-rating="4"
					    data-rateyo-num-stars="5"
					    data-rateyo-score="3">
                    </div>
                    <span class='result'>0</span>
                </div>
                
                <div class="item5">
                    <label for="cars">genre:</label>
                    <select name="genre" id="genre">
                        <option value="" selected disabled hidden>choose here</option>
                        <option value="classic">classic</option>
                        <option value="thriller">fantasy</option>
                        <option value="horror">horror</option>
                        <option value="memoir">memoir</option>
                        <option value="mystery">mystery</option>
                        <option value="nonfiction">nonfiction</option>
                        <option value="romance">romance</option>
                        <option value="sci-fi">sci-fi</option>
                    </select>
                </div>

				<input type="hidden" name="rating"><br>
				<input class="item6" id="description" name="description" type="text" placeholder="descriptions, thoughts, words of praise...">
	</div>
	<input type="submit" name="submit" value="Submit" class="btn btn-primary" id="publish-btn"/>
	</form>
	
	</div>

<div><span class="error_message" id="err_read"></span></div>
<script>
    $(function () {
		 $(".item4").rateYo().on("rateyo.change", function (e, data) {
				 var rating = data.rating;
				 $(this).parent().find('.score').text('score :'+ $(this).attr('data-rateyo-score'));
				 $(this).parent().find('.result').text('rating :'+ rating);
				 $(this).parent().find('input[name=rating]').val(rating); //add rating value to input field
		 });
 	});
</script>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
</body>
</html>
