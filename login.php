<?php
    
//for error messages
 $loginErrorMsg = "";

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";
include "validation/validation.php";
include "errorhandler/errorhandler.php";
include "errorhandler/sql_logging.php";
$con = connection();


// Login POST Action
try{
if(isset($_POST['login'])) {
   
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($email)||empty($password)){
        throw new customException("Fill all the fields");
          }
    $sql = "SELECT * FROM users WHERE email = '$email'";

    $user = $con->query($sql) or die ($con->error);
    $row = $user -> fetch_assoc();
    $total = $user->num_rows;
   
    if ($total > 0) {

        $db_password = $row['password'];

        if(password_verify($password, $db_password)) {
            session_destroy();//destroy first 02/10/2020
            session_start();//start again 02/10/2020
            session_regenerate_id(true); // regenerate a new identifier 02/10/2020
            $_SESSION['UserLogin'] = $row['email'];
            $_SESSION['Access'] = $row['access'];
            $_SESSION['ID'] = $row['userID'];
            echo header("Location: home.php");    
        
            // Insert A Log for A Login Success
         insertLog("Success", 0, "Successful login");
        } else{
            throw new customException("Invalid username and/or password! Please try again!");
            insertLog("ERROR", 1, "Invalid Input Credentials During Login");
        }
    } else {
        throw new customException("Invalid username and/or password! Please try again!");
        insertLog("ERROR", 1, "Invalid Input Credentials During Login");
    }
    $con->close();
}
}catch(customException $e){
    
    $loginErrorMsg=$e->errorMessage();

    // Login Failure Error
    insertLog("ERROR", 1, "Invalid Input Credentials During Login");
}



// Register POST Action
try{
if(isset($_POST['register'])) {
    // Empty by default
    $firstName = "";
    $lastName = "";
    $email = "";
    $password = '';

    // Validation
    //First Name
    if(isFirstNameValid($_POST['firstName']) == 1) {
        $firstName = formValidate($_POST['firstName']);
    } else {
        throw new customException("Error: Invalid First Name!");
        insertLog("ERROR", 1, "First Name Input Validation Error");
    }

     //Last Name
    if(isLastNameValid($_POST['lastName']) == 1) {
        $lastName = formValidate($_POST['lastName']);
    } else {
        throw new customException("Error: Invalid Last Name!");
        insertLog("ERROR", 1, "Last Name Input Validation Error");
    }

    // Email
    if(isEmailValid($_POST['email']) == 1) {
        $email = formValidate($_POST['email']);
    } else {
        throw new customException("Error: Invalid Email!");
        insertLog("ERROR", 1, "Email Input Validation Error");
    }

    // Password
    if(isPasswordValid($_POST['password']) == 1) {
        $password = $_POST['password'];
    } else {
        throw new customException("Error: Invalid Password!");
        insertLog("ERROR", 1, "Password Input Validation Error");
    }

    
    if($password != ""){
        $hash = password_hash($password, PASSWORD_BCRYPT);
    }
    
    // For duplicate email checking
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $user = $con->query($sql) or die ($con->error);
    $row = $user -> fetch_assoc();
    $total = $user->num_rows;

    if($total > 0) {
        throw new customException("Duplicate Email! Try Again");
    } else {
        $insertSql = "INSERT INTO `users` (`firstName`,`lastName`, `email`,`password`,`access`) VALUES ('$firstName', '$lastName', '$email','$hash','user')";
   
        // Rejection if it is empty	       
        if($firstName == "" || $lastName == "" || $email == "" || $password = "") {	
            throw new customException("Error: Invalid Input!");	
        } else {	
            $con->query($insertSql) or die($con->error);	
            $last_id = $con->insert_id;	
        }

        session_destroy();//destroy first 02/10/2020
        session_start();//start again 02/10/2020
        session_regenerate_id(true); // regenerate a new identifier 02/10/2020
        $_SESSION['UserLogin'] = $email;
        $_SESSION['Access'] = "user";
        $_SESSION['ID'] = $last_id;
        echo header("Location: home.php");  

        // Register success log       
        insertLog("INFO", 1, " User ID ".$last_id." Register Successful");
        insertLog("INFO", 1, " User ID ".$_SESSION['ID']." successfully login to the system");
    }

    $con->close();
}
}catch(customException $e){
    echo $e->errorMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- BRAND SECTION -->
            <div class="col-6 home-left">
                <h1 class="brand-title text-center "> Welcome To <br> The CCIT Forum.</h1>
                <div class="brand-list">
                    <ul>
                        <li>Share your thoughts!</li>
                        <li>Communicate with other CCIT students!</li>
                        <li>Be as one!</li>
                    </ul>
                </div>

                <div class="brand-subtitle">
                    <h4>"This is the subtitle put it here."</h3>
                </div>

                <br><br><br>
            </div>

            <div class="col-6 home-right">
                <!-- BRAND SECTION -->
                <div class="row">

                    <!-- Login Section -->
                    <div class="login">
                        <h5 class="text-muted text-center">National University - Manila</h5>
                        <p class="text-muted">College of Computing and Information Technologies</p>
                        <h1 class="text-center">Sign In.</h1>
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" autocomplete="on" class="form-control" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="pass" type="password" autocomplete="off" class="form-control"
                                            name="password"> <input type="checkbox" onclick="unhidePassword()"> Show
                                        Password </input>
                                    </div>

                                    <input type="submit" name="login" class="btn btn-primary float-right"
                                        value="Sign In"></input>
                                </form>
                                <?php if($loginErrorMsg != "") echo $loginErrorMsg . "<br>"; ?>
                                <p> Not yet a member? <button id="registerBtn" class="btn btn-link"> Sign Up Now!
                                    </button></p>
                            </div>
                        </div>
                    </div>

                    <!-- Register Section -->
                    <div class="register">
                        <h5 class="text-muted text-center">National University - Manila</h5>
                        <p class="text-muted">College of Computing and Information Technologies</p>
                        <h1 class="text-center">Sign Up.</h1>
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
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
                                        <input type="email" autocomplete="off" class="form-control" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="pass2" type="password" autocomplete="off" class="form-control"
                                            name="password"> <input type="checkbox" onclick="unhidePassword()"> Show
                                        Password </input>
                                        <small id="passwordHelpBlock" class="form-text text-muted">
                                            At least 8 characters long, <br> contains at least 1 uppercase, 1 lowercase,
                                            1 number, <br> 1 special character and SHOULD NOT start with a special
                                            character
                                        </small>
                                    </div>
                                    <input type="submit" name="register" class="btn btn-primary float-right"
                                        value="Sign Up"></input>
                                </form>
                                <p> Already a member? <button id="loginBtn" class="btn btn-link"> Sign In Here.
                                    </button></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer bg-light fixed-bottom">
        <div class="container">
            <span class="text-muted text-center footer-text"> The College of Computing and Information Technolgies
                Forum</span>
        </div>
    </footer>



    <!-- JQuery library -->
    <script src="js/jquery/jquery.min.js"></script>

    <!-- JQuery Script -->
    <script>
        $(".register").hide();

        $("#registerBtn").click(function () {
            $(".login").hide();
            $(".register").show();
            console.log("anyare");
        })

        $("#loginBtn").click(function () {
            $(".register").hide();
            $(".login").show();
            console.log("anyare");
        })

        function unhidePassword() {
            var x = document.getElementById("pass");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

            var y = document.getElementById("pass2");
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        }
    </script>
</body>

</html>