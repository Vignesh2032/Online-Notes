<?php
	// Session Start
	session_start();
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require "PhpMailer/src/Exception.php";
	require "PhpMailer/src/PHPMailer.php";
	require "PhpMailer/src/SMTP.php";

	// Connect to database
	include('connection.php');
	
	// check error inputs
		// define error messages
		
    $missingEmail = '<p><strong>Please enter your email address!</strong></p>';
    $invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';
    $errors = '';
	
	//Get Email
    if(empty($_POST["forgotemail"])){
        $errors .= $missingEmail;
    }
    else{
        $email = filter_var($_POST["forgotemail"], FILTER_SANITIZE_EMAIL);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors .= $invalidEmail;
		}
    }
	
	// If there are any errors print error message
    if($errors){
        $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
        echo $resultMessage;
		exit;
    }
	
	// Prepare variables for the query
	$email = mysqli_real_escape_string($link, $email);
	
	// Check the email exists in a users table
	$sql = "SELECT * FROM users WHERE email = '$email'";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo '<div class="alert alert-danger">Error running the query!</div>';
		exit;
	}
	
	$count = mysqli_num_rows($result);
	
	// If the email does not exits print error message
	if($count != 1){
		echo '<div class="alert alert-danger">This email does not exist on our database!</div>';
		exit;
	}
	
	// Get the user_id
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$user_id = $row['user_id'];
	
	// Create a unique activation code
	$key = bin2hex(openssl_random_pseudo_bytes(16));
	
	//insert user details and activation code in the forgotpassword table
	$time = time();
	$status = "pending";
	$sql = "INSERT INTO forgotpassword (user_id, rkey, time, status) VALUES ('$user_id', '$key', '$time', '$status')";
	$result = mysqli_query($link, $sql);
	
	if(!$result){
		echo '<div class="alert alert-danger">There was an error inserting the user details in the database!</div>';
		exit;
	}
	
	// Send the user an email with a link to resetpassword.php with user id and activation code.
	
	/*$message = "Please click on the link to reset your password:\n\n";
	$message .= "http://localhost:8000/resetpassword.php?user_id=$user_id&key=$key";
	
	// If email sent sucessfully print success message.
	if (mail($email, 'Reset your password', $message, 'From:'. 'vigneshgangadharan20@gmail.com')) {
		echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the link to reset your password.</div>";
	}*/

	try {
		//Server settings
		$mail = new PHPMailer(true);
		//$mail->SMTPDebug = 2;                                     // Enable verbose debug output
		$mail->isSMTP();                                            // Set mailer to use SMTP
		$mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = 'therivignesh20@gmail.com';       // SMTP username
		$mail->Password   = 'otqfnqvpydqppswf';                     // SMTP password (use an app-specific password if 2FA is enabled)
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `ssl` also accepted
		$mail->SMTPSecure = 'ssl';         // Enable TLS encryption, `ssl` also accepted
		//$mail->Port       = 587;                                    // TCP port to connect to
		$mail->Port       = 465;                                    // TCP port to connect to
	
		//Recipients
		$mail->setFrom('therivignesh20@gmail.com');
		$mail->addAddress($email, 'User');                          // Add a recipient
	
		// Content
		$mail->isHTML(true);                                        // Set email format to HTML
		$mail->Subject = 'Reset your Password';
		$mail->Body    = "Please click on the link to reset your password:<br><br>";
		$mail->Body   .= "http://localhost:8000/resetpassword.php?user_id=$user_id&key=$key";
		$mail->AltBody = "Please click on the link to reset your password:\n\n";
		$mail->AltBody .= "http://localhost:8000/resetpassword.php?user_id=$user_id&key=$key";
	
		$mail->send();
		echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the link to reset your password.</div>";
	} catch (Exception $e) {
		echo "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
	}
?>