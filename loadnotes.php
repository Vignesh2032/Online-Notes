<?php
    // Start the session
    session_start();
    include("connection.php");

    // Get the user_id
    $user_id = $_SESSION['user_id'];
    
    
    // Run a query to delete the empty notes
    $sql = "DELETE FROM notes WHERE note = ''";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        echo '<div class="alert alert-danger">An error occured!</div>';
            //echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
            exit;
    }
    // Run a query to look for notes corressponding to user_id
    $sql = "SELECT * FROM notes WHERE user_id = '$user_id' ORDER BY time DESC";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        echo '<div class="alert alert-danger">An error occured!</div>';
            //echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
            exit;
    }
    
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $note_id = $row['id'];
            $note = $row['note'];
            $time = $row['time'];
			$time = date("F d, Y h:i:s A", $time);
			
            echo "
				<div class='note'>
					<div class='col-xs-5 col-sm-3 delete'>
						<button class='btn-lg btn-danger' style='width:100%;'>delete</button>
					</div>
					<div class='noteheader' id='$note_id'>
						<div class='text'>$note</div>
						<div class='timetext'>$time</div>
					</div>;
				</div>
                ";
        }
    } 
    else {
        echo '<div class="alert alert-danger">You have not created any notes yet!</div>';
        
    }
    // Show notes or alert message
?>