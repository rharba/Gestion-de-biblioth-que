<?php
$conn = mysqli_connect("localhost","root","","books");
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
session_start();
?>