<?php

session_start();
include ('connection.php');
include ('functions.php');
date_default_timezone_set('Israel');
$userId = $_POST['user_id'];
$postTxt = addslashes($_POST['post_text']);
$isPrivate = $_POST['isPrivate'];
$picName = $_FILES ["file"] ["name"];
$date = date('Y-m-d H:i:s', time());
$query = "INSERT INTO post (user_id, post_text, post_pic_path,private, post_date) VALUES($userId, '".$postTxt."','".$picName."',$isPrivate, '".$date."' )";
$postId = 0;
if($mysqli->query($query) === true){
    $postId = $mysqli->insert_id;
}

if(isset($_FILES["file"]["type"])) {
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {

        $validExtensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);

        if ((($_FILES ["file"] ["type"] == "image/gif") || ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/jpg") || ($_FILES ["file"] ["type"] == "image/pjpeg") || ($_FILES ["file"] ["type"] == "image/x-png") || ($_FILES ["file"] ["type"] == "image/png")) && ($_FILES ["file"] ["size"] < 20000000) && in_array($file_extension, $validExtensions)) {
            if ($_FILES ["file"] ["error"] <= 0) {
                if (!file_exists('../users/' . $userId . '/' . $userId . $postId . '/upload/')) {
                    mkdir('../users/' . $userId . '/' . $userId . $postId . '/upload/', 0777, true);
                }

                $dir = '../users/' . $userId . '/' . $userId . $postId . '/upload/';

                if (move_uploaded_file($_FILES ['file'] ['tmp_name'], $dir . $_FILES ['file'] ['name'])) {
                    $query2 = "";
                }

            }
        }
    }
}

