<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "cyber_shield"; // 👈 Change this to your actual database name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
