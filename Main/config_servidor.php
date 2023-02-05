<?php
//$host = "localhost:3307" 127.0.0.1:3306
$host = "localhost";
$password = "123456"; //pc casa
$user = "root";
//$password = ""; password da escola;
$dbname = "pap";


$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>