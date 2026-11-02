<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlbanhang";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_POST["email"] ?? '';
$pass = $_POST["pass"] ?? '';

$sql = "select id, fullname, email from customers where email = '".$email."' and password = '".md5($pass)."'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  
  $_SESSION['email'] = $row['email'];
  $_SESSION['fullname'] = $row['fullname'];
  $_SESSION['id'] = $row['id'];
  
  header('Location: Homepage.php');
  exit();
  
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
  header('Refresh: 3;url=Login.php');
  exit();
}

$conn->close();
?>