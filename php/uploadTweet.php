<?php

session_start();
include('connection.php');
$tweet = $_POST['tweet'];
$sentiment = $_POST['sentiment'];

$query = "INSERT INTO tweets (tweet, sentiment) VALUES ('$tweet', '$sentiment' )";

$tweetID = 0;

if ($mysqli->query($query) === true) {
    //insert is successful
}

