<?php
// send_message.php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // Here you can add code to send email or save to database
    // For now, just redirect with success message
    
    $_SESSION['message_sent'] = true;
    header("Location: index.php#contact");
    exit();
}
?>