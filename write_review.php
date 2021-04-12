<?php
$booktitle = $bookauthor = $rating = $description = $genre = NULL;
$title_msg = $author_msg = $rating_msg = $description_msg =  $genre_msg = NULL;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   if (empty($_POST['title'])) 
      $title_msg = "Please enter a book title";
   else
   {
      $booktitle = trim($_POST['title']);
      // You may reset $name_msg and use it to determine
      // when to display an error message  
      // $name_msg = "";     
   }
			 
   if (empty($_POST['author']))
      $author_msg = "Please enter an author";
   else
   {
      $bookauthor = trim($_POST['author']);
      // You may reset $email_msg and use it to determine
      // when to display an error message
      // $email_msg = "";      
   }
					 
   if (empty($_POST['rating']))
      $rating_msg = "Please enter a rating";
   else
   {
      $rating = trim($_POST['rating']);
      // You may reset $comment_msg and use it to determine
      // when to display an error message
      // $comment_msg = "";      
   }

   if (empty($_POST['description']))
    $description_msg = "Please enter a description";
    else
    {
        $description = trim($_POST['description']);
        // You may reset $comment_msg and use it to determine
        // when to display an error message
        // $comment_msg = "";      
    }
    
    if (empty($_POST['genre'])) 
      $genre_msg = "Please select a genre";
    else
    {
      $genre = trim($_POST['genre']);
      // You may reset $name_msg and use it to determine
      // when to display an error message  
      // $name_msg = "";     
    }
}

?>
<!DOCTYPE html>
<!-- Contributions by Louisa Evola and Matthew Kovalenko-->
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
  			$("#nav-placeholder").load("navbar.html");
		});
  </script>
  	<br>

  <div class="outer-shell">
  <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<div class="grid-container">		
		<div class="title"><p>review your latest read! </p></div>
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
                        <option value="horror">horror</option>
                        <option value="memoir">memoir</option>
                        <option value="mystery">mystery</option>
                        <option value="nonfiction">nonfiction</option>
                        <option value="romance">romance</option>
                        <option value="scifi">sci-fi</option>
                        <option value="thriller">thriller</option>
                    </select>
                </div>

				<input type="hidden" name="rating"><br>
				<input class="item6" id="description" name="description" type="text" placeholder="descriptions, thoughts, words of praise...">
	</div>
	<input type="submit" value="Submit" class="btn btn-primary" id="publish-btn" onclick="publishPost()"/>
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

<?php
if ($booktitle != NULL && $bookauthor != NULL && $rating != NULL && $description != NULL && $genre!='')
{
   echo "<hr/>";
   echo "Thanks for your book review submission! <br>";
   echo "We can't wait to share your review of $bookauthor's $booktitle with our community at bookkeeper! <br>";
   echo "Your rating of $booktitle: $rating <br>";
   echo "Your description of $booktitle: $description <br>";	
   echo "Book genre: $genre";
}
else {
    echo "error: missing field(s); couldn't publish post";
} 
?>    
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
</body>
</html>
