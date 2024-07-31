<?php
    // The user is redirect to this file after clicking the link received by email and aiming at proving they own the new email address. 
    // Link contain Three get parameter: Email & New Email & Activation Key
    session_start();
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>New Email Activation</title>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-offset-1 col-sm-10 contactform">
                    <h1>Email Activation</h1>
                    <?php

                        //if email, new email or activation key is missing show an error.
                        if(!isset($_GET['email']) || !isset($_GET['newemail']) || !isset($_GET['key'])){
                            echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>';
                            exit;
                        }

                        //else store the values in three variables
                        $email = $_GET['email'];
                        $newemail = $_GET['newemail'];
                        $key = $_GET['key'];

                        $email = mysqli_real_escape_string($link, $email);
                        $newemail = mysqli_real_escape_string($link, $newemail);
                        $key = mysqli_real_escape_string($link, $key);

                        // Run query update email.
                        $sql = "UPDATE users SET email = '$newemail', activation2 = '0' WHERE email = '$email' AND activation2 = '$key' LIMIT 1";
                        $result = mysqli_query($link, $sql);
						
						// If a query is successful, show success message.
                        if(mysqli_affected_rows($link) == 1){
							session_destroy();
							setcookie('rememberme', '', time()-3600);
                            echo '<div class="alert alert-success">Your email has been updated.</div>';
                            echo '<a href="index.php" type="button" class="btn btn-lg btn-success">Log In</a>';
                        }
                        else{
                            echo '<div class="alert alert-danger">Your email could not be updated. Please try again later.</div>';
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