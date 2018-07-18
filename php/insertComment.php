<?php

session_start();
include('connection.php');
include('functions.php');
date_default_timezone_set('Israel');

$commentObj = new stdClass();
$userId = $_POST['user_id'];
$commentTxt = $_POST['val'];
$postId = $_POST['post_id'];
$date = date('Y-m-d H:i:s', time());
$postCommentId = 0;

$query = "INSERT INTO post_comment(user_id, post_id, comment, comment_datetime) VALUES ($userId, $postId, '" . $commentTxt . "', '" . $date . "')";
if ($mysqli->query($query) === true) {
    $postCommentId = $mysqli->insert_id;
}

if ($postCommentId != 0) {
    $query = "SELECT CONCAT(users.firstName, ' ', users.lastName) as userName, post_comment.comment, post_comment.comment_datetime  from users 
JOIN post_comment on users.id = post_comment.user_id
where post_comment.post_comment_id = $postCommentId";

    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $commentObj->userName = $row['userName'];
            $commentObj->comment = $row['comment'];
            $commentObj->datetime = $row['comment_datetime'];
            $commentObj->id = $postCommentId;
        }
    }
}

echo json_encode($commentObj);
