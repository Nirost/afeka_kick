<?php
/**
 * Created by PhpStorm.
 * User: Micha
 * Date: 6/5/2017
 * Time: 19:22 PM
 */

session_start();
include('connection.php');
include('functions.php');
ini_set('error_reporting', E_STRICT);

$friends = array();
$userId = $_POST['user_id'];
$friendsStr = "";

// Get friends
$getFriendsQuery = "SELECT users.friends FROM users WHERE users.id = $userId";
$getFriendsResult = $mysqli->query($getFriendsQuery);
if ($getFriendsResult->num_rows > 0) {
    while ($row = $getFriendsResult->fetch_assoc()) {
        $friendsStr = $row['friends'];
    }

}

if ($friendsStr === "" || $friendsStr === null)
    $friendsStr = $userId;
else
    $friendsStr .= "," . $userId;

$getPostsQuery = "SELECT post.user_id, post.post_id, post.post_date, post.post_pic_path, post.post_text, CONCAT(users.firstName, ' ', users.lastName) as userName, post.private
FROM `post`
JOIN users ON users.id =post.user_id
WHERE (post.user_id = $userId AND post.private = TRUE) OR (post.private = FALSE and post.user_id IN ($friendsStr)) GROUP BY post.post_date DESC";


$posts = array();
$comments = array();
$likes = array();


$getPostsResult = $mysqli->query($getPostsQuery);
$counter = 0;
if ($getPostsResult->num_rows > 0) {
    while ($row = $getPostsResult->fetch_assoc()) {
        $posts[$counter]->post_id = $row["post_id"];
        $posts[$counter]->user_id = $row["user_id"];
        $posts[$counter]->post_date = $row["post_date"];
        $posts[$counter]->post_text = $row["post_text"];
        $posts[$counter]->post_pic_path = $row["post_pic_path"];
        $posts[$counter]->private = $row["private"];
        $posts[$counter]->userName = $row["userName"];
        $counter++;
    }
}
for ($i = 0; $i < count($posts); $i++) {
    $comments = array();
    $likes = array();

    $userID = $posts[$i]->user_id;
    $postID = $posts[$i]->post_id;
    $imageName = $posts[$i]->post_pic_path;
    if ($imageName != null) {
        $path = '../users/' . $userID . '/' . $userID . $postID . '/upload/' . $imageName;
        $posts[$i]->pathImg = $path;
    }


    $getPostCommentsQuery = "SELECT post_comment.comment, post_comment.comment_datetime,CONCAT(users.firstName, ' ', users.lastName) as userName  FROM post_comment
JOIN users ON post_comment.user_id = users.id
WHERE post_comment.post_id = $postID";

    $getPostCommentsResult = $mysqli->query($getPostCommentsQuery);
    $counter2 = 0;
    if ($getPostCommentsResult->num_rows > 0) {
        while ($row = $getPostCommentsResult->fetch_assoc()) {
            $comments[$counter2]->comment = $row["comment"];
            $comments[$counter2]->comment_datetime = $row["comment_datetime"];
            $comments[$counter2]->userName = $row["userName"];
            $counter2++;
        }
        $posts[$i]->comments = $comments;

    }

    $getPostLikesQuery = "SELECT CONCAT(users.firstName, ' ', users.lastName) as userName FROM post_like JOIN users ON users.id = post_like.user_id WHERE post_like.post_id = $postID";
    $getPostLikesResult = $mysqli->query($getPostLikesQuery);
    $counter3 = 0;
    if ($getPostLikesResult->num_rows > 0) {
        while ($row = $getPostLikesResult->fetch_assoc()) {
            $likes[$counter3]->userName = $row["userName"];
            $counter3++;
        }
        $posts[$i]->likes = $likes;
        $posts[$i]->likeCount = count($likes);
    }

    $checkIfLikedQuery = "SELECT COUNT(*) as isLiked ,post_like.post_like_id FROM post_like WHERE post_like.post_id = $postID AND post_like.user_id = $userId";
    $checkIfLikedResult = $mysqli->query($checkIfLikedQuery);
    if ($checkIfLikedResult->num_rows > 0) {
        while ($row = $checkIfLikedResult->fetch_assoc()) {
            $posts[$i]->isLiked = $row["isLiked"];
            if (!(is_null($row["post_like_id"]))) {
                $posts[$i]->postLikeId = $row["post_like_id"];
            } else {
                $posts[$i]->postLikeId = "";
            }
        }
    }
}

echo json_encode($posts);

