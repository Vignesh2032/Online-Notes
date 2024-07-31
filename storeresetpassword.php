<!--This file receives: user_id, generated_key to reset password, password1 and password2-->
<!--This file then resets password for user_id if all checks are correct-->
<?php
	session_start();
    include("connection.php");
	
	//if user_id or activation key is missing show an error.
	if(!isset($_POST['user_id']) || !isset($_POST['key'])){
		echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>';
		exit;
	}

	//else store the values in two variables
	$user_id = $_POST['user_id'];
	$key = $_POST['key'];
	
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
	
	// Define error messages
	$missingPassword = '<p><strong>Please enter a password</strong></p>';
	$invalidPassword = '<p><strong>Your password should be at least 6 characters long and include one capital letter and one number!</strong></p>';
	$differentPassword = '<p><strong>Password don\'t match!</strong></p>';
	$missingPassword2 = '<p><strong>Please confirm your password!</strong></p>';
	$error = '';
	//Get Password
	if(empty($_POST["password"])){
		$error .= $missingPassword;
	}
	elseif(!(strlen($_POST["password"]) > 6 and preg_match('/[A-Z]/', $_POST["password"]) and preg_match('/[0-9]/', $_POST["password"]))){
		$error .= $invalidPassword;
	}		
	else{
		$password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
		if(empty($_POST["password2"])){
			$error .= $missingPassword2;
		}
		else{
			$password2 = filter_var($_POST["password2"], FILTER_SANITIZE_SPECIAL_CHARS);
			if($password !== $password2){
				$error .= $differentPassword;
			}
		}
	}
	
	// if there are any error print message
	if($error){
		echo '<div class="alert alert-danger">' . $error . '</div>';
		exit;
	}
	
	// Prepare the variable for the query
	
	$password = mysqli_real_escape_string($link, $password);
	$password = hash('sha256', $password);
	$user_id = mysqli_real_escape_string($link, $user_id);
	
	// Run query: Update the users password in the users table
	
	$sql = "UPDATE users SET password = '$password' WHERE user_id = '$user_id'";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo '<div class="alert alert-danger">There was a problem storing the new password in the database!</div>';
		exit;
	}
	
	// Set the key status to "used" in the forgotpassword table to prevent the key from being used twice.
	$sql = "UPDATE forgotpassword SET status = 'used' WHERE rkey = '$key' AND user_id = '$user_id'";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo '<div class="alert alert-danger">Error running the query!</div>';
	}
	else{
		echo '<div class="alert alert-success">New password has been update successfully! <a href="index.php">Login</a></div>';
	}
?>