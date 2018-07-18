<?php

session_start();
include('connection.php');
include('functions.php');
ini_set('error_reporting', E_STRICT);


$postId = $_POST['post_id'];
$postText = $_POST['post_text'];
$isPrivate = $_POST['private'];
$userId = $_POST['user_id'];

$query = "UPDATE post SET post.private = $isPrivate, post.post_text = '" . $postText . "' WHERE post.post_id = $postId";

$results = $mysqli->query($query);

if ($results)
    echo json_encode(true);
else {
    echo json_encode(false);
}
