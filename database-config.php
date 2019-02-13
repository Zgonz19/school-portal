<?php
   // define database related variables
   $database = 'project';
   $host = 'localhost';
   $user = 'root';
   $pass = '';



   /* Database credentials. Assuming you are running MySQL
   server with default setting (user 'root' with no password) */
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_NAME', 'project');
   // try to conncet to database
   $dbh = new PDO("mysql:dbname={$database};host={$host};port={3306}", $user, $pass);
   $conn = new mysqli($host, $user, $pass, $database);
   $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
   if(!$dbh){

      echo "unable to connect to database";
   }

   if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
   } 

   if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
   }
?>