<?php
session_start();

require_once __DIR__ . "/../Class/User.php";
require_once __DIR__ . "/../helper/MailHelper.php";
require_once __DIR__ . "./../helper/auth.php";

requireRole(['admin', 'instructor']);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /course-management/ui/admin_dashboard.php");
    exit();
}

$id = (int) $_GET['id'];

$userObj = new User();

$student = $userObj->getUserById($id);

if (!$student) {
    $_SESSION['error'] = "User not found";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$result = $userObj->deleteUsers($id);

if ($result['status']) {

    $email = $student['email'];
    $subject = "Account Deactivation Notice - Student Enrollment System";

    $message = "
    <html>
        <body style='font-family: Arial; line-height: 1.6;'>
            <h2 style='color: #d9534f;'>Account Deactivated</h2>

            <p>Dear {$student['name']},</p>

            <p>Your account has been <strong>deactivated</strong> by the administrator.</p>

            <p>You will no longer be able to access the system.</p>

            <p>If this is a mistake, contact admin.</p>

            <br>
            <p><strong>Student Enrollment Team</strong></p>
        </body>
    </html>
    ";

    $mail = MailHelper::sendEmail($email, $subject, $message);

    if (!$mail['status']) {
        $_SESSION['error'] = "User deleted, but email failed";
    }
} else {
    $_SESSION['error'] = $result['message'];
}

// ✅ Proper redirect
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
