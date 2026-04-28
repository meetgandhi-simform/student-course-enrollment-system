<?php

require_once __DIR__ . "./../Class/User.php";
require_once __DIR__ . "./../validator/Validator.php";
require_once __DIR__ . "./../helper/MailHelper.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = new User();

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
        $allErrors = implode("\\n", $errors);

        echo "<script>
            alert('$allErrors');
            window.location.href = '/course-management/ui/register.php';
        </script>";
        exit();
    }

    $id = $user->createUser(
        $name['data'],
        $email['data'],
        $password['data'],
        $phone['data'],
        $role
    );

    if ($id) {
        $subject = 'Registration Success Emai;';
        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #2c3e50; text-align: center;'>🎓 Student Enrollment System</h2>

                <p>Hi,</p>
                <p>Thank you for registering with our platform.</p>
                <p style='background-color: #f4f6f7; padding: 10px; border-left: 4px solid #3498db;'>
                Your account has been successfully created.
                </p>
            
                <p>You can now login and explore the system.</p>

                    <div style='text-align: center; margin-top: 20px;'>
                        <a href='http://localhost:8103/course-management/ui/login.php' 
                        style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                        Login Now
                        </a>
                    </div>

                    <br>

                    <p>Best Regards,<br>
                    <b>Student Enrollment Team</b></p>
            </div>";

        $mail = MailHelper::sendEmail($email['data'], $subject, $message);
        if ($mail['status']) {
            echo "<script>
                    alert('Registered Successfully!');
                    window.location.href = '/course-management/ui/login.php';
                </script>";
            exit;
        } else {
            echo "<script>alert(" . json_encode($mail['message']) . ");</script>";
        }
    } else {
        echo "Error!";
    }
}
