<?php

require __DIR__ . "./../../validator/Validator.php";
require __DIR__ . "./../../class/User.php";

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


    fgetcsv($handle, 1000, ",", '"', "\\");

    while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
        $rowNumber++;

        $errors = [];

        $name     = trim($row[0] ?? '');
        $email    = trim($row[1] ?? '');
        $password = trim($row[2] ?? '');
        $phone    = trim($row[3] ?? '');
        $role     = trim($row[4] ?? '');

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

    $totalRows = count($data);
    $invalidRows = array_filter($data, fn($row) => !$row['is_valid']);
    $invalidCount = count($invalidRows);
    $inserted = $result['inserted'] ?? 0;

    if ($result['status'] && $inserted > 0 && $invalidCount === 0) {

        echo "<script>
            alert('All {$inserted} users uploaded successfully!');
            window.location.href = '/course-management/ui/admin/adminDashboard.php';
        </script>";
    } elseif ($result['status'] && $inserted > 0 && $invalidCount > 0) {

        $allErrors = [];

        foreach ($invalidRows as $row) {
            $allErrors[] = "Row {$row['row']}: " . implode(", ", $row['errors']);
        }

        $errorString = json_encode(implode("\n", $allErrors));

        echo "<script>
                alert('Partial Upload:\\nInserted: {$inserted}\\nFailed: {$invalidCount}\\n\\nErrors:\\n' + $errorString);
                window.location.href = '/course-management/ui/admin/adminDashboard.php';
            </script>";
    } else {


        $allErrors = [];

        foreach ($invalidRows as $row) {
            $allErrors[] = "Row {$row['row']}: " . implode(", ", $row['errors']);
        }

        if (empty($allErrors)) {
            $allErrors[] = 'No valid rows inserted into database!';
        }

        $errorString = json_encode(implode("\n", $allErrors));

        echo "<script>
                alert('Upload Failed:\\n' + $errorString);
                window.location.href = '/course-management/ui/admin/adminDashboard.php';
            </script>";
    }
}
?>