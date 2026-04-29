<?php
session_start();

require_once __DIR__ . "./../Class/User.php";
require_once __DIR__ . "./../validator/Validator.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $name = Validator::name($_POST['name']);
    $oldName = Validator::name($_POST['oldname']);
    $email = Validator::email($_POST['email']);
    $oldEmail = Validator::email($_POST['oldemail']);
    $phone = Validator::phone($_POST['phone']);
    $oldPhone = Validator::phone($_POST['oldphone']);

    if ($name !== $oldName) {
        $data['name'] = $name['data'];
    }
    if ($email !== $oldEmail) {
        $data['email'] = $email['data'];
    }
    if ($phone !== $oldPhone) {
        $data['phone'] = $phone['data'];
    }

    $errors = [];
    if (!$name['status']) $errors[] = $name['message'];
    if (!$email['status']) $errors[] = $email['message'];
    if (!$phone['status']) $errors[] = $phone['message'];

    if (!empty($errors)) {
        $allErrors = implode("\n", $errors);

        echo "<script>
        alert(" . json_encode($allErrors) . ");
        window.history.back();
    </script>";
        exit();
    }

    $user = new User();
    try {
        $result = $user->updateUser($id, $data);
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . htmlspecialchars($e->getMessage()) . "');
            window.history.back();
        </script>";
    }

    if ($result['status']) {
        echo "<script>
            alert('Profile Updated Sucessfully!! ');
            window.history.back();    
        </script>";
        exit();
    } else {
        echo $result['message'];
    }
}
