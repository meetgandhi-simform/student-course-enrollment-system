<?php
session_start();

require_once __DIR__ . "./../Class/User.php";
require_once __DIR__ . "./../validator/Validator.php";
require_once __DIR__ . "./../helper/auth.php";
require_once __DIR__ . "./../helper/MailHelper.php";

requireLogin();
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userObj = new User();

    $name = Validator::name($_POST['name']);
    $email = Validator::email($_POST['email']);
    $password = Validator::password($_POST['password']);
    $phone = Validator::phone($_POST['phone']);
    $role = $_POST['role'];

    $errors = [];

    if (!$name['status']) $errors[] = $name['message'];
    if (!$email['status']) $errors[] = $email['message'];
    if (!$password['status']) $errors[] = $password['message'];
    if (!$phone['status']) $errors[] = $phone['message'];


    if (!empty($errors)) {
        $allErrors = implode("\n", $errors);

        echo "<script>
        alert(" . json_encode($allErrors) . ");
        window.location.href = '/course-management/ui/admin_create_user.php';
    </script>";
        exit();
    }

    try {
        $result = $userObj->createUser(
            $name['data'],
            $email['data'],
            $password['data'],
            $phone['data'],
            $role
        );
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = '/course-management/ui/admin_dashboard.php';
        </script>";
    }
    if ($result['status'] == true) {
        $emailAddress = $email['data'];
        $passwordOfUser = $password['data'];
        $subject = "Your Account Has Been Created – Student Enrollment System";
        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>

            <h2 style='color: #2c3e50; text-align: center;'>🎓 Student Enrollment System</h2>
            <p>Hello,</p>
            <p>An administrator has created an account for you on the <b>Student Enrollment System</b>.</p>

            <p style='background-color: #f4f6f7; padding: 10px; border-left: 4px solid #3498db;'>
                <b>Role:</b> $role <br>
                <b>Email:</b> $emailAddress <br>
                <b>Password:</b>$passwordOfUser
            </p>

            <p>You can now log in using your credentials.</p>

                <div style='text-align: center; margin-top: 20px;'>
                    <a href='http://localhost:8103/course-management/ui/login.php' 
                        style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                        Login Now
                    </a>
                </div>

            <br>
            <p>If you did not expect this email, please contact your administrator.</p>
            <p>Best Regards,<br>
            <b>Student Enrollment Team</b></p>
        </div>";

        $mail = MailHelper::sendEmail($email['data'], $subject, $message);
        if ($mail['status'] == true) {
            echo "<script>
                    alert('User Created Successfully');
                    window.location.href = '/course-management/ui/admin_dashboard.php';
                </script>";
        } else {
            echo "<script>alert(" . json_encode($mail['message']) . ");</script>";
        }
    } else {
        echo "<script>
        alert(" . json_encode($result['message']) . ");
        window.location.href = '/course-management/ui/admin_dashboard.php';
    </script>";
    }
}
