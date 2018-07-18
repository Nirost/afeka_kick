<?php
session_start();
include('connection.php');
include('functions.php');
ini_set('error_reporting', E_STRICT);

$allUsers = array();
$userFriends = array();
$notFriends = array();

$obj = array();
$friendsStr = "";
$userId = $_POST['user_id'];
$index = 0;
$count2 = 0;



// Get all users who are not the current user
$getAllUserQuery = "SELECT CONCAT(users.firstName, ' ', users.lastName) as userName, users.id FROM users WHERE users.id <> $userId";
$result = $mysqli->query($getAllUserQuery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allUsers[$index]->userName = $row['userName'];
        $allUsers[$index]->userId = $row['id'];
        $index++;
    }
}

// Get friends of the current user
$getUserFriendsQuery = "SELECT users.friends FROM users WHERE users.id = $userId";
$result = $mysqli->query($getUserFriendsQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $friendsStr = $row['friends'];
    }
}

// Current user has friends
if ($friendsStr !== NULL) {
    // Get names (and IDs) of the friends
    $getNamesOfFriendsQuery = "SELECT CONCAT(users.firstName, ' ', users.lastName) as userName, users.id FROM users WHERE users.id IN ($friendsStr)";

    $result = $mysqli->query($getNamesOfFriendsQuery);
    if ($result->num_rows > 0) {
        $index = 0;
        while ($row = $result->fetch_assoc()) {
            $userFriends[$index]->userName = $row['userName'];
            $userFriends[$index]->userId = $row['id'];
            $index++;
        }
    }
}

$notFriendsIndex = 0;

for ($i = 0; $i < count($allUsers); $i++) {
    if (strpos($friendsStr, $allUsers[$i]->userId) === false) {
        // User is not in the friend list of the current user
        $notFriends[$notFriendsIndex]->userId = $allUsers[$i]->userId;
        $notFriends[$notFriendsIndex]->userName = $allUsers[$i]->userName;
        $notFriendsIndex++;
    }
}

$obj[0]->friends = $notFriends;
$obj[0]->myFriends = $userFriends;

echo json_encode($obj);