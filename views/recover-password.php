<?php
session_start();

require "../config/database.php";
require '../config/helper.php';
require '../config/mailer.php';

$email = $_POST["email"];


$token=bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60*30);



$sql="UPDATE admin
     SET reset_token_hash = ?,
         reset_token_expires=?
     WHERE AMAIL= ?";

$stmt = $db->prepare($sql);

if ($stmt === false) {
    // Display the error if statement preparation fails
    die("SQL error: " . $db->error);
}

// Bind parameters
$stmt->bind_param("sss", $token_hash, $expiry, $email);

// Execute the query
$stmt->execute();

// Check if any rows were affected
if ($stmt->affected_rows > 0) {
    
    
    $mail = new MailHandler();
    $mail = $mail->getMailerInstance();
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email); 
    $mail->Subject = "password reset";
    $mail->Body = <<<END

    Click <a href="localhost/new project/views/auth/rest-password.php?token=$token ">here</a>
    to reset your password.
    END;
    try {
        $mail->send();
        if(isset($email)){
            $_SESSION['email']=$email;
            header('Location:'. url("/views/sendmail.php"));
            exit();
        }

        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
} else {
    echo "No account found with that email.";
    
}