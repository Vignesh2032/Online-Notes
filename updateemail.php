<?php
	//Start the session
	session_start();
	
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require "PhpMailer/src/Exception.php";
	require "PhpMailer/src/PHPMailer.php";
	require "PhpMailer/src/SMTP.php";

	// Connect to database
	include("connection.php");
	
	// Get the user_id and email sent through Ajax
	$user_id = $_SESSION["user_id"];
	$newemail = $_POST["email"];
	
	// Check if new email exists
	$sql = "SELECT * FROM users WHERE email = '$newemail'";
	$result = mysqli_query($link, $sql);
	
	$count = mysqli_num_rows($result);
	if($count > 0){
		echo '<div class="alert alert-danger">There is already as user registered with that email! Please choose another one!</div>';
		exit;
	}
	// Get the current email
	$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
	$result = mysqli_query($link, $sql);
	
	$count = mysqli_num_rows($result);
	
	if($count == 1){
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$email = $row["email"];
	}
	else{
		echo "<div class='alert alert-danger'>There was an error retriving the email from the database!</div>";
		exit;
	}
	// Create a new activation code
	$activationkey = bin2hex(openssl_random_pseudo_bytes(16));
	
	// Insert new activation code in the users table
	$sql = "UPDATE users SET activation2 = '$activationkey' WHERE user_id = '$user_id'";
	$result = mysqli_query($link, $sql);
	if(!$result){
		echo "<div class='alert alert-danger'>There was an error inserting the user details in the database!</div>";
		exit;
	}
	// Send email with a link to activatenewwmail.php with current emaail, new email and activation code
	
	/*$message = "Please click on the link to prove that you own this email:\n\n";
		$message .= "http://localhost:8000/activatenewwmail.php?email=" .urlencode($email) ."&newemail=" .urlencode($newemail) . "&key=$activationkey";
		if(mail($newemail,'Email Update for your Online Notes App', $message, 'From:'. 'vigneshgangadharan20@gmail.com')){
			echo "<div class='alert alert-success'>An email has been sent to $newemail. Please click on the link to prove you own that email address.</div>";
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
    $mail->Subject = 'Email Update for your Online Notes App';
    $mail->Body    = "Please click on the link to prove that you own this email:<br><br>";
    $mail->Body   .= "http://localhost:8000/activatenewwmail.php?email=" .urlencode($email) ."&newemail=" .urlencode($newemail) . "&key=$activationkey";
    $mail->AltBody = "Please click on the link to prove that you own this email:\n\n";
    $mail->AltBody .= "http://localhost:8000/activatenewwmail.php?email=" .urlencode($email) ."&newemail=" .urlencode($newemail) . "&key=$activationkey";

    $mail->send();
    echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the link to prove you own that email address.</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
}
?>