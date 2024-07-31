<?php
	//Start the session
	session_start();
	
	// Connect to database
	include("connection.php");
	
	// Define error messages
	$missingCurrentPassword = '<p><strong>Please enter your Current Password!</strong></p>';
	$incorrectCurrentPassword = '<p><strong>The password entered is incorrect!</strong></p>';
	$missingPassword = '<p><strong>Please enter a new Password!</strong></p>';
	$InvalidPassword = '<p><strong>Your password should be at least 6 characters long and include one capital letter and one number!</strong></p>';
	$differentPassword = '<p><strong>Password don\'t match!</strong></p>';
	$missingPassword2 = '<p><strong>Please confirm your password!</strong></p>';
	$error = '';
	
	// Check for errors
	if(empty($_POST["currentpassword"])){
		$error .= $missingCurrentPassword;
	}
	else{
		$currentPassword = $_POST["currentpassword"];
		$currentPassword = filter_var($currentPassword, FILTER_SANITIZE_SPECIAL_CHARS);
		$currentPassword = mysqli_real_escape_string($link, $currentPassword);
		$currentPassword = hash('sha256',$currentPassword);
		
		$user_id = $_SESSION["user_id"];
		$sql = "SELECT password FROM users WHERE user_id = '$user_id'";
		$result = mysqli_query($link, $sql);
		
		$count = mysqli_num_rows($result);
		if($count !== 1){
			echo '<div class="alert alert-danger">There was a problem running the query</div>';
		}
		else{
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if($currentPassword != $row["password"]){
				$error .= $incorrectCurrentPassword;
			}
		}
	}
	
	if (empty($_POST['password'])) {
		$error .= $missingPassword;
	} elseif (!(strlen($_POST['password']) > 6 && preg_match('/[A-Z]/', $_POST['password']) && preg_match('/[0-9]/', $_POST['password']))) {
		$error .= $InvalidPassword;
	} else {
		$password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if (empty($_POST['password2'])) {
			$error .= $missingPassword2;
		} else {
			$password2 = filter_var($_POST['password2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if ($password !== $password2) {
				$error .= $differentPassword;
			}
		}
	}
	
	// if there is an error print error message
	if($error){
		$resultMessage = '<div class="alert alert-danger">' . $error . '</div>';
		echo $resultMessage;
		exit;
	}
	
	// prepare the variables for query 
	$password = mysqli_real_escape_string($link, $password);
	$password = hash('sha256', $password);
	
	// run query and update password	
	$sql = "UPDATE users SET password = '$password' WHERE user_id = '$user_id'";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo '<div class="alert alert-danger">The password could not be reset. Please try again later.</div>';
	}
	else{
		echo '<div class="alert alert-success">Your password has been updated successfully.</div>';
	}
?>