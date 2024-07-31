<?php
// Session Start
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PhpMailer/src/Exception.php";
require "PhpMailer/src/PHPMailer.php";
require "PhpMailer/src/SMTP.php";

include('connection.php');

// Define Error Messages
$missingUsername = '<p><strong>Please enter a Username!</strong></p>';
$missingEmail = '<p><strong>Please enter your Email Address!</strong></p>';
$InvalidEmail = '<p><strong>Please enter a valid Email Address!</strong></p>';
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$InvalidPassword = '<p><strong>Your password should be at least 6 characters long and include one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Password don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your password!</strong></p>';
$error = '';

// Get Username
if (empty($_POST['username'])) {
    $error .= $missingUsername;
} else {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
}

// Get Email
if (empty($_POST['email'])) {
    $error .= $missingEmail;
} else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= $InvalidEmail;
    }
}

// Get Password
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

// If there are any errors, print the error message
if ($error) {
    $resultMessage = '<div class="alert alert-danger">' . $error . '</div>';
    echo $resultMessage;
    exit;
}

// no errors

// Prepare variables for the Queries
$username = mysqli_real_escape_string($link, $username);
$email = mysqli_real_escape_string($link, $email);
$password = mysqli_real_escape_string($link, $password);

//$password = md5($password);
$password = hash('sha256', $password);


// Check if the username already exists in the table
$sql = "SELECT * FROM USERS WHERE username = '$username'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo '<div class="alert alert-danger">Error running the query!</div>';
    echo '<div class="alert alert-danger">'. mysqli_errno($link) .'</div>';
    exit;
}

$results = mysqli_num_rows($result);

if ($results) {
    echo '<div class="alert alert-danger">That username is already registered. Do you want to login?</div>';
    exit;
}

// Check if the email already exists in the table
$sql = "SELECT * FROM USERS WHERE email = '$email'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo '<div class="alert alert-danger">Error running the query!</div>';
    exit;
}

$results = mysqli_num_rows($result);

if ($results) {
    echo '<div class="alert alert-danger">That email is already registered. Do you want to login?</div>';
    exit;
}

// Create a unique activation code.

$activationkey = bin2hex(openssl_random_pseudo_bytes(16));
        // byte: unit of data = 8 bits
        // bit: 0 or 1
        // 16 * 8 = 128 bits
        // 2 * 2 * 2 ......... * 2 (128 times)
        // converting (binary to hexa)  (2*2*2*2) * (2*2*2*2) *....... * (2*2*2*2)
        // 128 / 4 = 32 Characters

$sql = "INSERT INTO USERS (username, email, password, activation)
                        VALUES ('$username', '$email', '$password', '$activationkey')";

$result = mysqli_query($link, $sql);

if (!$result) {
    echo '<div class="alert alert-danger">Error was an error inserting the users details in the database!</div>';
    exit;
}

// Send the user an email with a link to activation.php with there mail and activation code.

/*$message = "Please click on the link to activate your account:\n\n";
$message .= "http://localhost:8000/activate.php?email=" .urlencode($email) . "&key=$activationkey";
if(mail($email,'Confirm your Registration', $message, 'From:'. 'vigneshgangadharan20@gmail.com')){
    echo "<div class='alert alert-success'>Thank you for registering! A confirmation email has been sent to $email. Please click on the activation link to activate your account.</div>";
}*/

// -------------------------------------------------

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
    $mail->Subject = 'Confirm your Registration';
    $mail->Body    = "Please click on the link to activate your account:<br><br>";
    $mail->Body   .= "http://localhost:8000/activate.php?email=" . urlencode($email) . "&key=$activationkey";
    $mail->AltBody = "Please click on the link to activate your account:\n\n";
    $mail->AltBody .= "http://localhost:8000/activate.php?email=" . urlencode($email) . "&key=$activationkey";

    $mail->send();
    echo "<div class='alert alert-success'>Thank you for registering! A confirmation email has been sent to $email. Please click on the activation link to activate your account.</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
}
?>
