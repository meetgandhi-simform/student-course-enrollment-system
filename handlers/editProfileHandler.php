<?php
session_start();

require_once "/var/www/html/course-management/Class/User.php";
require_once "/var/www/html/course-management/validator/Validator.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $name = Validator::name($_POST['name']);
    $email = Validator::email($_POST['email']);
    $phone = Validator::phone($_POST['phone']);

    if($name !== $_POST['oldname']){
        $data['name'] = $name['data'];
    }
    if($email !== $_POST['oldemail']){
        $data['email'] = $email['data'];
    }
    if($phone !== $_POST['oldphone']){
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
        window.location.href = '/course-management/ui/admin_create_user.php';
    </script>";
        exit();
    }


    $user = new User();
    try{
    $result = $user->updateUser($id, $data);
    }catch(Exception $e){
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
?>
