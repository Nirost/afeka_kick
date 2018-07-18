<?php
session_start();
include('connection.php');
include('functions.php');
ini_set('error_reporting', E_STRICT);
$likeObj = array();
$Obj = array();
$userId = $_POST['user_id'];
$postId = $_POST['post_id'];
$postLikeId = 0;
$query = "INSERT into post_like(post_id, user_id) VALUES($postId, $userId)";

if ($mysqli->query($query) === true) {
    $postLikeId = $mysqli->insert_id;
}

if ($postLikeId != 0) {
    $query = "SELECT  post_like.post_like_id FROM post_like JOIN users ON users.id = post_like.user_id WHERE post_like.user_id = $userId AND post_like.post_id = $postId";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Obj[0]->postLikeId = $row['post_like_id'];
        }
    }
}

$counter = 0;
$query = "SELECT CONCAT(users.firstName, ' ', users.lastName) AS userName FROM post_like JOIN users ON users.id = post_like.user_id WHERE post_like.post_id = $postId ";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $likeObj[$counter]->userNameLikes = $row['userName'];
        $counter++;
    }
}

$Obj[0]->likes = $likeObj;
$Obj[0]->likeCount = count($likeObj);
echo json_encode($Obj);