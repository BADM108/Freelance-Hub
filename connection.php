<?php
$servername = "localhost";   //  hosting server
$username = "root";       // MySQL username
$password = "";   //didnt use a password since localhost
$dbname = "freelancershub";    // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
