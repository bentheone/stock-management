<?php
$username = 'root';
$password = '';
$servername = 'localhost';
$db_name = 'stock';

$conn = mysqli_connect($servername, $username, $password,  $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

