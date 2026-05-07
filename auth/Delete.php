<?php
session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../class/User.php";
require_once __DIR__ . "/../helper/MailHelper.php";
require_once __DIR__ . "./../helper/AuthHelper.php";
require_once __DIR__ . "./../class/EmailQueue.php";

AuthHelper::requireRole(['admin', 'instructor']);

$emailQueueObj = new EmailQueue();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid User ID'
    ]);

    exit();
}

$id = (int) $_POST['id'];

$userObj = new User();

$user = $userObj->getUserById($id);

if (!$user) {
    echo json_encode([
        'status' => false,
        'message' => 'User not found'
    ]);

    exit();
}

$result = $userObj->deleteUsers($id);

if ($result['status']) {

    $email = $user['email'];
    $subject = "Account Deactivation Notice - Student Enrollment System";

    $message = "
    <html>
        <body style='font-family: Arial; line-height: 1.6;'>
            <h2 style='color: #d9534f;'>Account Deactivated</h2>

            <p>Dear {$user['name']},</p>

            <p>Your account has been <strong>deactivated</strong> by the administrator.</p>

            <p>You will no longer be able to access the system.</p>

            <p>If this is a mistake, contact admin.</p>

            <br>
            <p><strong>Student Enrollment Team</strong></p>
        </body>
    </html>
    ";

    $queue = $emailQueueObj->addEmail($email, $subject, $message);

    if (!$queue['status']) {
        error_log("Email Queue Failed: " . $queue['message']);
    }
    echo json_encode([
        'status' => true,
        'message' => 'User deleted successfully'
    ]);
    exit();
}
echo json_encode([
    'status' => false,
    'message' => $result['message']
]);
