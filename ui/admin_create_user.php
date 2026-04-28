<?php
require_once __DIR__ . "./../helper/auth.php";
require_once __DIR__ . "./../handlers/registerHandler.php";
requireRole('admin');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
</head>

<body>

    <?php require 'navbar.php'; ?>

    <div class="form-container">
        <div class="toggle-container">
            <button class="active" onclick="showForm('manual', this)">Manual Entry</button>
            <button onclick="showForm('csv', this)">Upload CSV</button>
        </div>

        <div id="manual-form">
            <h2>Create User</h2>
            <form method="POST" action="/course-management/handlers/registerHandler.php">

                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <label for="phone">Phone</label>
                <input type="tel" name="phone" id="phone" required>

                <label for="role">Role</label>
                <select name="role" id="role">
                    <option value="Admin">Admin</option>
                    <option value="Instructor">Instructor</option>
                </select>

                <button type="submit">Create User</button>
            </form>
        </div>

        <div id="csv-form" style="display:none;">
            <form action="/course-management/handlers/csvUploadHandler.php"
                method="POST"
                enctype="multipart/form-data">

                <label for="file">Upload CSV File</label>
                <input type="file" name="file" id="file" accept=".csv" required>

                <button type="submit">Upload CSV</button>
            </form>
        </div>

        <a href="admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>
    <script>
        function showForm(type, el) {
            document.getElementById('manual-form').style.display = type === 'manual' ? 'block' : 'none';
            document.getElementById('csv-form').style.display = type === 'csv' ? 'block' : 'none';

            const buttons = document.querySelectorAll('.toggle-container button');
            buttons.forEach(btn => btn.classList.remove('active'));

            el.classList.add('active');
        }
    </script>
</body>

</html>