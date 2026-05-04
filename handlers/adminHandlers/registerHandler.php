<?php
session_start();

require_once __DIR__ . "./../../class/User.php";
require_once __DIR__ . "./../../validator/Validator.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";
require_once __DIR__ . "./../../helper/MailHelper.php";
require_once __DIR__ . "./../../class/EmailQueue.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userObj = new User();
    $emailQueueObj = new EmailQueue();

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
            window.location.href = '/course-management/ui/admin/adminDashboard.php';
        </script>";
        exit();
    }
    if ($result['status']) {
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
                    <a href='http://172.16.7.30:8103/course-management/ui/login.php' 
                        style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                        Login Now
                    </a>
                </div>

            <br>
            <p>If you did not expect this email, please contact your administrator.</p>
            <p>Best Regards,<br>
            <b>Student Enrollment Team</b></p>
        </div>";
        $queue = $emailQueueObj->addEmail($email['data'], $subject, $message);

        if (!$queue['status']) {
            error_log("Email Queue Failed: " . $queue['message']);
        }
        echo "<script>
                    alert('User created successfully');
                    window.location.href = '/course-management/ui/admin/adminDashboard.php';
                </script>";
        exit();
    } else {
        echo "<script>
        alert(" . json_encode($result['message']) . ");
        window.location.href = '/course-management/ui/admin/adminCreateUser.php';
    </script>";
    }
}
