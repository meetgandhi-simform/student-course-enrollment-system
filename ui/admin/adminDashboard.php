<?php

require_once __DIR__ . "./../../helper/AuthHelper.php";
require_once __DIR__ . "./../../handlers/adminHandlers/adminDashboardHandler.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('admin');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
    <title>Admin Dashboard</title>
</head>

<?php include_once 'navbar.php'; ?>

<body>
    <div class="dashboard-header">
        <h1>Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?> 👋</h1>
        <p>Here’s what’s happening in your system</p>
    </div>

    <div class="dashboard-cards">

        <div class="card">
            <h3>Total Admins</h3>
            <p><?= $totalAdmins ?></p>
        </div>

        <div class="card">
            <h3>Total Students</h3>
            <p><?= $totalStudents ?></p>
        </div>

        <div class="card">
            <h3>Total Instructors</h3>
            <p><?= $totalInstructors ?></p>
        </div>

        <div class="card">
            <h3>Total Courses</h3>
            <p><?= $totalCourses ?></p>
        </div>

        <div class="card">
            <h3>Total Enrollments</h3>
            <p><?= $totalEnrollments ?></p>
        </div>

        <div class="card">
            <h3>Active Users</h3>
            <p><?= $totalActiveUsers ?></p>
        </div>

    </div>
</body>

</html>