<?php

require __DIR__ . "./../../handlers/instructorHandlers/instructorDashboardHandler.php";
require_once __DIR__ . "./../../helper/AuthHelper.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('instructor');

?>

<?php include_once 'instructorNavbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
    <title>Instructor Dashboard</title>
</head>

<body>
    <div class="dashboard-header">
        <h1>Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Instructor') ?> 👋</h1>
        <p>Here's what's happening in your system</p>
    </div>

    <div class="dashboard-cards">

        <div class="card">
            <h3>Total Students</h3>
            <p><?= $totalStudents ?></p>
        </div>

        <div class="card">
            <h3>Total Courses</h3>
            <p><?= $totalCourseWithInstructor ?></p>
        </div>

    </div>
</body>

</html>