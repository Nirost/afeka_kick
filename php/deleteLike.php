<?php

session_start();
include('connection.php');
include('functions.php');
ini_set('error_reporting', E_STRICT);
$postId = $_POST['post_id'];
$postLikeId = $_POST['post_like_id'];

$likeObj = array();
$obj = array();

$query = "DELETE FROM post_like WHERE post_like_id = $postLikeId";
$mysqli->query($query);
$count = mysqli_affected_rows($mysqli);

if ($count > 0) {
    $counter = 0;
    $query = "SELECT CONCAT(users.firstName, ' ', users.lastName) AS userName FROM post_like JOIN users ON users.id = post_like.user_id WHERE post_like.post_id = $postId ";
    $res = $mysqli->query($query);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $likeObj[$counter]->userNameLikes = $row['userName'];
            $counter++;
        }
    }
    $obj[0]->likes = $likeObj;
    $obj[0]->likeCount = count($likeObj);
    echo json_encode($obj);
} else {
    echo json_encode(false);
}


