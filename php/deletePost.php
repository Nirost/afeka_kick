<?php

session_start();
include('connection.php');
include('functions.php');
ini_set('error_reporting', E_STRICT);
$postId = $_POST['post_id'];

if ($postId > 0) {
    $postQuery = "DELETE FROM post WHERE post.post_id = $postId";
    $commentsQuery = "DELETE FROM post_comment WHERE post_comment.post_id = $postId";
    $likesQuery = "DELETE FROM post_like WHERE post_like.post_id = $postId";

    $mysqli->query($postQuery);
    $mysqli->query($commentsQuery);
    $mysqli->query($likesQuery);

}

echo json_encode("true");