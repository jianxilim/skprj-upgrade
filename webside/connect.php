<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "kehadiran";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "Fail connect." . $conn->connect_error;
}

?>
