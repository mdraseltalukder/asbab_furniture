
<?php
// <!-- connection with mysqli -->
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asbab_furniture";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}