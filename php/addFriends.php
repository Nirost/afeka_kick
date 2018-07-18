<?php

session_start();

include('connection.php');
include('functions.php');

$newFriendsStr = $_POST['friendStr'];
$friendsStr = "";
ini_set('error_reporting', E_STRICT);
$userId = $_POST['user_id'];


$query = "SELECT users.friends FROM users WHERE users.id = $userId";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {
    if (is_null($row['friends'])) {
        $friendsStr = "";
    } else {
        $friendsStr = $row['friends'];
    }
}

if ($friendsStr == "") {
    $friendsStr = $newFriendsStr;
} else {
    $friendsStr .= ', ' . $newFriendsStr;
}

$query = "UPDATE users SET users.friends = '" . $friendsStr . "' WHERE users.id = $userId";
$results = $mysqli->query($query);

if ($results) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}










