<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket";

$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
} 
mysqli_select_db($conn, $dbname);

?>