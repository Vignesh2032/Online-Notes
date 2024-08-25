<?php
    // Start the session
    session_start();
    include("connection.php");

    // Get the user_id
	$user_id = $_SESSION['user_id'];
    // Get the current time
	date_default_timezone_set('Asia/Kolkata');
	$time = time();
    // Run a query to create new note
	$sql = "INSERT INTO notes (user_id, note, time) VALUES('$user_id', '', $time)";
	$result = mysqli_query($link, $sql);
	if(!$result){
		echo 'error';
	}
	else{
		//mysqli_insert_id returns the auto-generated id used in the last query.
		echo mysqli_insert_id($link);
	}
?>