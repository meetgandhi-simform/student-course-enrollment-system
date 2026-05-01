<?php

require __DIR__ . "./../../handlers/studentHandlers/studentDashboardHandler.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('student');
?>

<?php include_once 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
    <title>Student Dashboard</title>
</head>

<body>
    <div class="dashboard-header">
        <h1>Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Student') ?> 👋</h1>
        <p>Here's what's happening</p>
    </div>

    <div class="dashboard-cards">

        <div class="card">
            <h3>Enrolled Courses</h3>
            <p><?= $totalEnrollments ?></p>
        </div>

        <div class="card">
            <h3>Available Courses</h3>
            <p><?= $totalCourses ?></p>
        </div>

    </div>
</body>

</html>