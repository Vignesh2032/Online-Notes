<?php
    // The user is redirect to this file after clicking the activation link
    // Signup link contain Two get parameter: Email & Activation Key
    session_start();
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Account Activation</title>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-offset-1 col-sm-10 contactform">
                    <h1>Account Activation</h1>
                    <?php

                        //if email or activation key is missing show an error.
                        if(!isset($_GET['email']) || !isset($_GET['key'])){
                            echo '<div class="alert alert-danger">There was an error. Please click on the activation link you received by email.</div>';
                            exit;
                        }

                        //else store the values in two variables
                        $email = $_GET['email'];
                        $key = $_GET['key'];

                        $email = mysqli_real_escape_string($link, $email);
                        $key = mysqli_real_escape_string($link, $key);

                        // Set the activation field to activated for the provided email.
                        $sql = "UPDATE users SET activation = 'activated' WHERE email = '$email' AND activation = '$key' LIMIT 1";
                        $result = mysqli_query($link, $sql);

                        if(mysqli_affected_rows($link) == 1){
                            echo '<div class="alert alert-success">Your account has been activated.</div>';
                            echo '<a href="index.php" type="button" class="btn btn-lg btn-success">Log In</a>';
                        }
                        else{
                            echo '<div class="alert alert-danger">Your account could not be activated. Please try again later.</div>';
                            echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>