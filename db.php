<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "portfolio_db"; // Make sure this matches your PHPMyAdmin name!

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>