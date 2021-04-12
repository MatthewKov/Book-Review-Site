<?php

$hostname = 'usersrv01.cs.virginia.edu';

$dbname = 'le9ds_book_project';

$username = 'le9ds';
$password = 'F4ll2020!!';

$dsn = "mysql:host=$hostname;dbname=$dbname";

try 
{
   $db = new PDO($dsn, $username, $password);
   //echo "<p>You are connected to the database</p>";
}
catch (PDOException $e) 
{

   $error_message = $e->getMessage();        
   echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)
{
   $error_message = $e->getMessage();
   echo "<p>Error message: $error_message </p>";
}

?>
