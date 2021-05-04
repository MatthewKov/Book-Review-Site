<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

require('db_connect.php');

global $db;

$postdata = file_get_contents("php://input");
echo $postdata;

$request = json_decode($postdata, true);

//$query = "";
//if($list == "books_read") // $list has the same name as the table
	$query = "INSERT INTO books_read VALUES (:user, :title, :author)";
//else
//	$query = "INSERT INTO books_to_read VALUES (:user, :title, :author)";

$statement = $db->prepare($query);
$statement->bindValue(':user', 'matthew_kov');
$statement->bindValue(':title', $request['title_read']);
$statement->bindValue(':author', $request['author_read']);
$statement->execute();
$statement->closeCursor();

// $books_read_query = "SELECT * FROM books_read";
// $read_statement = $db->prepare($books_read_query);
// //$read_statement->bindValue(':usr', $_SESSION['user']);
// $read_statement->execute();
// $books_read_list = $read_statement->fetchAll();
// // foreach($books_read_list as $k => $v) {
// // 	echo $k . " " . $v;
// // }
// $read_statement->closeCursor();

// $books_to_read_query = "SELECT * FROM books_to_read";
// $to_read_statement = $db->prepare($books_to_read_query);
// //$to_read_statement->bindValue(':usr', $_SESSION['user']);
// $to_read_statement->execute();
// $books_to_read_list = $to_read_statement->fetchAll();
// $to_read_statement->closeCursor();

// $data = [];
// //$data[0]['bio'] = $profile_list;
// $data[0]['booksRead'] = $books_read_list;
// $data[0]['booksToRead'] = $books_to_read_list;

// // foreach($data[0] as $k => $v) {
// // 	echo $k . " " . $v;
// // }
// //echo $data[0]['booksRead'];
// //echo json_encode($data[0]['booksRead']);

// echo json_encode(['content'=>$data]);

?>