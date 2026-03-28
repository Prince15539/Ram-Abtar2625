<?php
$conn = mysqli_connect("localhost", "root", "", "prince_portfolio");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }
session_start();
?>