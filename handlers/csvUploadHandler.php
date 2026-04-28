<?php

require __DIR__ . "./../validator/Validator.php";
require __DIR__ . "./../Class/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['file'])) {
        die("File upload failed!");
    }

    $file = $_FILES['file'];

    $fileType = mime_content_type($file['tmp_name']);
    $allowedTypes = ['text/plain', 'text/csv', 'application/vnd.ms-excel'];

    if (!in_array($fileType, $allowedTypes)) {
        die("Only CSV files are allowed!");
    }

    $handle = fopen($file['tmp_name'], 'r');

    if (!$handle) {
        die("Unable to open file!");
    }

    $data = [];
    $rowNumber = 0;

    fgetcsv($handle, 1000, ",", '"', "\\"); // skip header

    while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
        $rowNumber++;

        $errors = [];

        $name     = $row[0] ?? '';
        $email    = $row[1] ?? '';
        $password = $row[2] ?? '';
        $phone    = $row[3] ?? '';
        $role     = $row[4] ?? '';

        if (count($row) < 5) {
            $errors[] = "Missing columns";
        }

        $nameValidation     = Validator::name($name);
        $emailValidation    = Validator::email($email);
        $passwordValidation = Validator::password($password);
        $phoneValidation    = Validator::phone($phone);
        $roleValidation     = Validator::role($role);

        if (!$nameValidation['status']) {
            $errors[] = $nameValidation['message'];
        }

        if (!$emailValidation['status']) {
            $errors[] = $emailValidation['message'];
        }

        if (!$passwordValidation['status']) {
            $errors[] = $passwordValidation['message'];
        }

        if (!$phoneValidation['status']) {
            $errors[] = $phoneValidation['message'];
        }

        if (!$roleValidation['status']) {
            $errors[] = $roleValidation['message'];
        }

        $data[] = [
            'row' => $rowNumber,
            'name' => $nameValidation['status'] ? $nameValidation['data'] : $name,
            'email' => $emailValidation['status'] ? $emailValidation['data'] : $email,
            'password' => $passwordValidation['status'] ? $passwordValidation['data'] : $password,
            'phone' => $phoneValidation['status'] ? $phoneValidation['data'] : $phone,
            'role' => $roleValidation['status'] ? $roleValidation['data'] : $role,
            'errors' => $errors,
            'is_valid' => empty($errors)
        ];
    }

    fclose($handle);


    $userObj = new User();
    $result = $userObj->bulkInsertUsers($data);
    if ($result['status']) {
        echo "<script>
                 alert('Users uploaded successfully!');
                 window.location.href = '/course-management/ui/admin_dashboard.php';
            </script>";
    } else {
        echo "<script>
                alert(".json_encode($result['message']). ");
                window.location.href = '/course-management/ui/admin_dashboard.php';
        </script>";
    }
}

?>