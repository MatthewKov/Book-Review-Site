<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

session_start();
require('db_connect.php');

global $db;

// $profile_query = "SELECT bio FROM user WHERE username = :usr";
// $profile_statement = $db->prepare($profile_query);
// $profile_statement->bindValue(':usr', $_SESSION['user']);
// $profile_statement->execute();
// $profile_list = $profile_statement->fetch();
// $profile_statement->closeCursor();

$books_read_query = "SELECT * FROM books_read";
$read_statement = $db->prepare($books_read_query);
//$read_statement->bindValue(':usr', $_SESSION['user']);
$read_statement->execute();
$books_read_list = $read_statement->fetchAll();
// foreach($books_read_list as $k => $v) {
// 	echo $k . " " . $v;
// }
$read_statement->closeCursor();

$books_to_read_query = "SELECT * FROM books_to_read";
$to_read_statement = $db->prepare($books_to_read_query);
//$to_read_statement->bindValue(':usr', $_SESSION['user']);
$to_read_statement->execute();
$books_to_read_list = $to_read_statement->fetchAll();
$to_read_statement->closeCursor();

$data = [];
//$data[0]['bio'] = $profile_list;
$data[0]['booksRead'] = $books_read_list;
$data[0]['booksToRead'] = $books_to_read_list;

// foreach($data[0] as $k => $v) {
// 	echo $k . " " . $v;
// }
//echo $data[0]['booksRead'];
//echo json_encode($data[0]['booksRead']);

echo json_encode(['content'=>$data]);

?>