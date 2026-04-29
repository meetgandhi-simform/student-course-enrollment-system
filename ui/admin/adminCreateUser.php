<?php
require_once __DIR__ . "./../../handlers/adminHandlers/registerHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
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
            <form method="POST" action="/course-management/handlers/adminHandlers/registerHandler.php">

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
            <form action="/course-management/handlers/adminHandlers/csvUploadHandler.php"
                method="POST"
                enctype="multipart/form-data">

                <label for="file">Upload CSV File</label>
                <input type="file" name="file" id="file" accept=".csv" required>

                <button type="submit">Upload CSV</button>
            </form>
        </div>
    </div>
    <script src="./../js/createUser.js"></script>
</body>

</html>