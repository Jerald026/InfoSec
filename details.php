<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Access']) && $_SESSION['Access'] == "admin") {
    echo header("Location: home.php");
}

include_once "connections/connection.php";
$con = connection();

$id = $_GET['ID'];

// Get user
$sql = "SELECT * FROM users WHERE userID = '$id'";
$users = $con->query($sql) or die($con->error);
$row = $users->fetch_assoc();

// Get user's post
$userPostSQL = "SELECT users.userID, users.name, users.email, posts.postID, posts.subject, posts.body, posts.dateAdded ".
            "FROM users JOIN posts ".
            "ON users.userID = posts.userID ".
            "WHERE users.userID = ".$id.
            " ORDER BY posts.dateAdded DESC";
$userPosts = $con->query($userPostSQL) or die($con->error);
$userPostRow = $userPosts->fetch_assoc();


if(isset($_SESSION['UserLogin'])) {
    echo "<div class='float-right'> Welcome <b> ".$_SESSION['UserLogin']." </b> Role: <b> ".$_SESSION['Access']."</b></div> <br>";
} else {
    echo "Welcome guest!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=" , initial-scale=1.0">
    <title>View Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

</head>

<body>
    <div class="container">

        <h1 class="text-center"> CCIT Forum Admin </h1>
        <h3 class="text-center"> View Details </h3>
        <br> <br>
        <!-- <a class="btn btn-dark" href="/ccitforum/index.php"> Back to List </a> <br> -->

        <div class="card">
            <div class="card-body" <div class="view">
                <h2> <b>Name</b> : <?php echo $row['name'];?> </h2>
                <h2> <b>Email</b> : <?php echo $row['email'];?> </h2>
                <h2> <b>Access</b>: <?php echo $row['access'];?> </h2>
            </div>
            <a id="loginBtn" class="btn btn-link" href="/ccitforum/accounts.php"> Back to User's List. </a>
        </div>

        <br>

        <!-- User's Posts -->

        <?php if($userPosts->num_rows > 0) { ?>
        <h3> <?php echo $userPostRow['name']?> Posts </h3>
            <?php do { ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> <?php echo $userPostRow['subject'] ?></h4>
                    <small class="card-subtitle">
                        <?php echo "Posted by <b>".$userPostRow['name'].' </b>'.' '.$userPostRow['email'].' '.$userPostRow['dateAdded']  ?>
                    </small>
                </div>
                <div class="card-body">
                    <?php echo $userPostRow['body'] ?>
                </div>
            </div> <br>
        
            <?php } while($userPostRow = $userPosts->fetch_assoc()) ?>
        <?php } else { echo "<div class='display-4'> No posts yet! </div>"; } ?>
            
    </div>
</body>
</html>