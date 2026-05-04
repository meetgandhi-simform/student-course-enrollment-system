<?php

require_once __DIR__ . "./../class/User.php";
require_once __DIR__ . "./../validator/Validator.php";
require_once __DIR__ . "./../helper/MailHelper.php";
require_once __DIR__ . "./../class/EmailQueue.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    header('Content-Type: application/json');

    $user = new User();
    $emailQueueobj = new EmailQueue();

    $name = Validator::name($_POST['name']);
    $email = Validator::email($_POST['email']);
    $password = Validator::password($_POST['password']);
    $phone = Validator::phone($_POST['phone']);
    $role = 'student';

    $errors = [];

    if (!$name['status']) $errors['name'] = $name['message'];
    if (!$email['status']) $errors['email'] = $email['message'];
    if (!$password['status']) $errors['password'] = $password['message'];
    if (!$phone['status']) $errors['phone'] = $phone['message'];


    if (!empty($errors)) {
        echo json_encode([
            "status" => false,
            "errors" => $errors
        ]);
        exit();
    }

    $result = $user->createUser(
        $name['data'],
        $email['data'],
        $password['data'],
        $phone['data'],
        $role
    );

    if ($result['status']) {

        $subject = 'Registration Successful Email';

        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
            <h2 style='color: #2c3e50; text-align: center;'>🎓 Student Enrollment System</h2>

            <p>Hi <b>{$name['data']}</b>,</p>

            <p>Thank you for registering with our platform.</p>

            <p style='background-color: #f4f6f7; padding: 10px; border-left: 4px solid #3498db;'>
                Your account has been successfully created.
            </p>

            <p>You can now login and explore the system.</p>

            <div style='text-align: center; margin-top: 20px;'>
                <a href='http://172.16.7.30:8103/course-management/ui/login.php' 
                style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                    Login Now
                </a>
            </div>

            <br>

            <p>Best Regards,<br>
            <b>Student Enrollment Team</b></p>
        </div>
    ";
        $queueResponse = $emailQueueobj->addEmail(
            $email['data'],
            $subject,
            $message
        );
        if (!$queueResponse['status']) {
            error_log("Email Queue Failed: " . $queueResponse['message']);
        }
        echo json_encode([
            "status" => true,
            "message" => "Registered Successfully"
        ]);
        exit;
    } else {
        echo json_encode([
            "status" => false,
            "message" => $result['message']
        ]);
    }
}
