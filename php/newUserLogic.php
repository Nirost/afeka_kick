<?php

session_start();
include('connection.php');
include('functions.php');
$firstName = $_POST['regFirstName'];
$lastName = $_POST['regLastName'];
$email = $_POST['regEmail'];
$password = $_POST['regPassword'];
$hashedPassword = hashPassword($password, $email);


if (isset($_POST["submit"])) {
    $selectQuery = "SELECT * FROM users WHERE email='$email' AND password = '$hashedPassword' LIMIT 1";
    $insertQuery = "INSERT INTO users(firstName, lastName, password, email,logged_in) VALUES ('" . $firstName . "','" . $lastName . "','" . $hashedPassword . "','" . $email . "',1)";

    $result = $mysqli->query($selectQuery);

    if ($result->num_rows <= 0) {
        // No such existing user
        $result = $mysqli->query($insertQuery);
        $userId = $mysqli->insert_id;
        if ($result === true) {
            $_SESSION['userID'] = $userId;
            if (!file_exists('../users/' . $userId . '')) {
                mkdir('../users/' . $userId . '', 0777, true);
            }
            header('location: ../index.php?userID=' . $userId);
        } else {
            header('location: ../newUser.php');
            phpAlert('Error');
        }

    } else {
        phpAlert("User already exists.");
    }


}
