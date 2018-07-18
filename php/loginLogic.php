<?php

session_start();
include('connection.php');
include('functions.php');
$email = $_POST['loginEmail'];
$password = $_POST['loginPassword'];
$hashedPassword = hashPassword($password, $email);

if (isset($_POST["submit"])) {
    $query = "SELECT * FROM users WHERE email='$email' AND password = '$hashedPassword' LIMIT 1";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userId = $row['id'];
            $_SESSION['userID'] = $userId;
            $_SESSION['curPage'] = 0;
            $_SESSION['first'] = 1;
            if (isset($userId)) {
                header("location: ../index.php?userID=$userId");
            }
        }
    } else {
        header('location: ../login.php');
    }
}