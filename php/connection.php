<?php

header('Content-type: text/xhtml; charset=windows-1255');
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "root";
$mysql_database = "AfekaFace";
$prefix = "";

$mysqli = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
$mysqli->set_charset("utf8");


if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}
