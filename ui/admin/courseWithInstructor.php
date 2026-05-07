<?php
session_start();
require_once __DIR__ . "./../../helper/AuthHelper.php";
AuthHelper::requireRole('admin');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <link rel="stylesheet" href="./../css/index.css">
    <title>Course With Instructor</title>
</head>

<?php require 'navbar.php'; ?>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <h2>Course With Instructor</h2>
        <table id="courseTable" class="custom-table">

            <thead>
                <tr>
                    <th>Id</th>
                    <th>Course Name</th>
                    <th>Seats Left</th>
                    <th>Instructor Id</th>
                    <th>Instructor Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>
    </div>
    <script src="./../js/admin/getAllCourses.js"></script>
</body>

</html>