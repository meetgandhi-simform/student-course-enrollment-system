<?php
session_start();

require_once __DIR__ . "./../../class/User.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /course-management/ui/login.php");
    exit();
}

$userObj = new User();
$user_id = $_SESSION['user_id'];


$user = $userObj->getUserById($user_id);

if (!$user) {
    die("User not found");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php require 'navbar.php'; ?>

    <div class="form-container">
        <h2>Edit Profile</h2>

        <form method="POST" action="/course-management/handlers/editProfileHandler.php">

            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <label for="name">Name</label>
            <input type="hidden" name="oldname" id="name" value="<?= htmlspecialchars($user['name']) ?>">
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>">

            <label for="email">Email</label>
            <input type="hidden" name="oldemail" id="email" value="<?= htmlspecialchars($user['email']) ?>">
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>">

            <label for="phone">Phone</label>
            <input type="hidden" name="oldphone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>">
            <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>">

            <button type="submit">Update Profile</button>
        </form>

    </div>

</body>

</html>