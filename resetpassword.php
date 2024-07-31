<!--This file receive the user_id and password to create the new password-->
<!--This file display a form to input new password-->

<?php
    session_start();
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Reset Password</title>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
		<style>
            h1{
                color:purple;   
            }
            .contactForm{
                border:1px solid #7c73f6;
                margin-top: 50px;
                border-radius: 15px;
            }
        </style> 
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-offset-1 col-sm-10 contactform">
                    <h1>Reset Password</h1>
					<div id="resultmessage"></div>
                    <?php

                        //if user_id or activation key is missing show an error.
                        if(!isset($_GET['user_id']) || !isset($_GET['key'])){
                            echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>';
                            exit;
                        }

                        //else store the values in two variables
                        $user_id = $_GET['user_id'];
                        $key = $_GET['key'];
						
						// define a time variable minus 24 hours
						$time = time() - 86400;

                        $user_id = mysqli_real_escape_string($link, $user_id);
                        $key = mysqli_real_escape_string($link, $key);
                        //$time = mysqli_real_escape_string($link, $time);

                        // Check combination of user_id and key exists and less than 24hr old.
                        $sql = "SELECT user_id FROM forgotpassword WHERE user_id = '$user_id' AND rkey = '$key' AND time > '$time' AND status = 'pending'";
                        $result = mysqli_query($link, $sql);
						
						if(!$result){
							echo '<div class="alert alert-danger">Error running the query!</div>';
							exit;
						}
						
						// If the combination does not exist
						// show an error message
						$count = mysqli_num_rows($result);
						if($count !== 1){
							echo '<div class="alert alert-danger">Please try again!</div>';
							exit;
						}
						
						// Print reset password form with hidden user_id and key fields
						echo "
						<form method=post id='passwordreset'>
							<input type='hidden' name='key' value=$key>
							<input type='hidden' name='user_id' value=$user_id>
							<div class='form-group'>
								<label for='password'>Enter your new Password:</label>
								<input type='password' id='password' name='password' placeholder='Enter Password' class='form-control'>
							</div>
							
							<div class='form-group'>
								<label for='password'>Re-enter Password:</label>
								<input type='password' id='password2' name='password2' placeholder='Re-enter Password' class='form-control'>
							</div>
							
							<input type='submit' class='btn btn-lg btn-success' name='resetpassword' value='Reset Password'>
						</form>
						"
                    ?>
                </div>
            </div>
        </div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		
		<!--Script for Ajax call to storeresetpassword.php which processes form data.-->
		<script>
			$('#passwordreset').submit(function (event) {
			event.preventDefault();

			//Collect the user inputs
			var datatopost = $(this).serializeArray();
			//console.log(datatopost);
			$.ajax({
				url: 'storeresetpassword.php',
				type: "POST",
				data: datatopost,
				success: function (data) {
					
					$('#resultmessage').html(data);
				},
				error: function () {
					$('#resultmessage').html("<div class= 'alert alert-danger'>There was an error with the Ajax call. Please try again later.</div>");
				}
			});
		});
		</script>
    </body>
</html>