<?php
$host = 'localhost';
$user = 'root'; // use your DB user
$pass = '';     // use your DB password
$dbname = 'course_suggestion';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>
