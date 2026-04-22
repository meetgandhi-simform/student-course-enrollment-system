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
        MailHelper::registerEmail($email['data']);
    } else {
        echo "Error!";
    }
}
