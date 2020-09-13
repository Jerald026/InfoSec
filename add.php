<?php

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";

$con = connection();

if(isset($_SESSION['Access']) && $_SESSION['Access'] == "admin") {
    echo "<div class='float-right'> Welcome <b> ".$_SESSION['UserLogin']." </b> Role: <b> ".$_SESSION['Access']."</b></div> <br>";
} else {
    echo header("Location: home.php");
}

if(isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "INSERT INTO `users` (`firstName`,`lastName`,`email`,`password`,`access`) VALUES ('$firstName','$lastName','$email','$password', 'user')";

    $con->query($sql) or die($con->error);

   echo header("Location: accounts.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

    <div class="container">

        <div class="register">
           <h1 class="text-center"> CCIT Forum Admin </h1>
            <h3 class="text-center">Add New User </h1>
            <a id="loginBtn" class="btn btn-dark float-right" href="/ccitforum/"> Back to User's List. </a><br><br>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" onSubmit="return confirm('Do you really want to add this user?'">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="name" class="form-control" name="firstName">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="name" class="form-control" name="lastName">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="pass" type="password" class="form-control" name="password"> <input type="checkbox" onclick="unhidePassword()" > Show Password </input>
                        </div>
                        <input type="submit" name="submit" class="btn btn-success float-right" value="Add New User"></input>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- JQuery library -->
    <script src="js/jquery/jquery.min.js"></script>

    <!-- JQuery Script -->
    <script>
        function unhidePassword() {
            var x = document.getElementById("pass");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>