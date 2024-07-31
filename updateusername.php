<?php
	//Start the session
	session_start();
	
	// Connect to database
	include("connection.php");
	
	// Get the user_id
	$user_id = $_SESSION["user_id"];
	
	// Get the new username through Ajax call
	$username = $_POST["username"];
	
	// Run the query and update the username
	$sql = "UPDATE users SET username = '$username' WHERE user_id = '$user_id'";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo '<div class="alert alert-danger">There was an error updating the new username in the database!</div>';
	}
	
?>