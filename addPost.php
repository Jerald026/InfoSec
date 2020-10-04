<?php

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";

$con = connection();

if(isset($_POST['addPost'])) {
    $userID = $_SESSION['ID'];
    $subject = $_POST['postSubject'];
    $body = $_POST['postBody'];
    $dateAdded = date('Y-m-d H:i:s');;
    $sql = "INSERT INTO `posts` (`userID`,`subject`,`body`,`dateAdded`) VALUES ('$userID','$subject','$body', '$dateAdded')";

    $con->query($sql) or die($con->error);
    
    echo header("Location: home.php");
}

if(isset($_POST['myPost'])) {
    $userID = $_SESSION['ID'];
    $subject = $_POST['postSubject'];
    $body = $_POST['postBody'];
    $dateAdded = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `posts` (`userID`,`subject`,`body`,`dateAdded`) VALUES ('$userID','$subject','$body', '$dateAdded')";

    $con->query($sql) or die($con->error);
    
    echo header("Location: myPosts.php");
}
?>