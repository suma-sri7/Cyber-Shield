<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

session_start();

$mysqli = new mysqli("localhost", "root", "", "cyber_shield");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $otp = rand(100000, 999999);

    // Insert user into DB
    $stmt = $mysqli->prepare("INSERT INTO users (email, password, otp_code, is_verified) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $email, $password, $otp);
    $stmt->execute();
    $stmt->close();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vamsi113377@gmail.com';      // ✅ REPLACE THIS
        $mail->Password = 'ecvg ddmt udid fmkd';         // ✅ REPLACE THIS
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'Cyber Shield');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP for Cyber Shield';
        $mail->Body = "Hello,\n\nYour OTP is: $otp\n\nPlease enter it on the verification page.";

        $mail->send();
        header("Location: verify.php?email=$email");
        exit;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
