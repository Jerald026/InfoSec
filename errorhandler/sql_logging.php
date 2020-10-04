<?php

function db_setup() {
    include_once "connections/connection.php";
    $con = connection();
    return $con;
}

function insertLog($type, $lvl, $message) {
    $con = db_setup();
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `syslogs` (`log_type`, `level`, `timestamp`, `message`) VALUES ('$type', '$lvl', '$date', '$message')";
    $con->query($sql) or die($con->error);	
    $con->close();
    return $sql;
}
