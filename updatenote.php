<?php
    // Start the session
    session_start();
    include("connection.php");

    // Get the id of the note sent through Ajax
	$id = $_POST["id"];
    // Get the content of the note
	$note = $_POST["note"];
	
    // Get the current time
	date_default_timezone_set('Asia/Kolkata');
	$time = time();
    // Run a query to update the note
	$sql = "UPDATE notes SET note = '$note', time = '$time' WHERE id = '$id'";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo 'error';
	}
	
?>