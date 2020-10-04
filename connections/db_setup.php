<?php

function db_setup() {
    include_once "connection.php";
    $con = connection();
    return $con;
}


function createDatabase() {
    include_once "connection.php";
    $con = DBLessConnection();
    $sql = "CREATE DATABASE ccitdb";
    $con->query($sql) or die($con->error);
    $con->close();
}

function createTables($con) {
    $usersTable = 
        "CREATE TABLE `users` (".
            "`userID` int AUTO_INCREMENT PRIMARY KEY,".
            "`firstName` varchar(50) NOT NULL,".
            "`lastName` varchar(50) NOT NULL,".
            "`email` varchar(50) NOT NULL,".
            "`password` varchar(255) NOT NULL,".
            "`access` varchar(10) NOT NULL) ";
    $con->query($usersTable) or die ($con->error);

    $password = password_hash('Admin123!', PASSWORD_BCRYPT);
    $insertAdmin = "INSERT INTO `users` (`userID`, `firstName`, `lastName`, `email`, `password`, `access`) VALUES (NULL, 'Admin', 'Admin', 'admin@admin.com', '$password', 'admin')";
    $con->query($insertAdmin) or die ($con->error);

    $postsTable =
        "CREATE TABLE `posts` (".
            "`postID` int AUTO_INCREMENT PRIMARY KEY,".
            "`userID` int NOT NULL,".
            "`subject` varchar(75) NOT NULL,".
            "`body` varchar(255) NOT NULL,".
            "`dateAdded` datetime NOT NULL)";

    $postsForeignKey = "ALTER TABLE posts ADD FOREIGN KEY (userID) REFERENCES users(userID);";
    $con->query($postsTable) or die ($con->error);
    $con->query($postsForeignKey) or die ($con->error);

    $syslogsTable =
    "CREATE TABLE `syslogs` (".
        "`Log_ID` int AUTO_INCREMENT PRIMARY KEY,".
        "`Log_Type` varchar(10) NOT NULL,".
        "`Level` int(11) NOT NULL,".
        "`Timestamp` datetime NOT NULL,".
        "`Message` varchar(255) NOT NULL)";
    $con->query($syslogsTable) or die ($con->error);

    $con->close();
}



createDatabase();
$con = db_setup();
createTables($con);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p> Database Created! <a class="btn btn-link" href="/"> Go to Login!
        </button></p>
</body>

</html>
?>
