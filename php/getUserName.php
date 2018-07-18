<?php
session_start();
include('connection.php');
$userID = $_SESSION['userID'];
$query = "SELECT firstName, lastName FROM users WHERE id=$userID";
$result = $mysqli->query($query);
$name = new stdClass();

while ($row = $result->fetch_assoc()) {
    $name->first = $row["firstName"];
    $name->last = $row["lastName"];
}

echo json_encode($name);