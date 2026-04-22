<?php
require_once __DIR__ . './../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class MailHelper
{
    public static function registerEmail($email)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['emailhost'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['emailusername'];
            $mail->Password   = $_ENV['emailpassword'];
            $mail->SMTPSecure = $_ENV['smtpsecure'];
            $mail->Port       = $_ENV['emailport'];

            $mail->setFrom($_ENV['emailusername'], $_ENV['name']);
            $mail->addAddress($email);
            $mail->Subject = 'PHPMailer Test';
            $mail->Body    = 'Thank You for Register with Student Enrollment system';

            $mail->send();
            echo "<script>
                alert('Registered Successfully!');
                window.location.href = '/course-management/ui/login.php';
            </script>";
        } catch (Exception $e) {
            echo "Mail failed: {$mail->ErrorInfo}";
        }
        exit();
    }
}
