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

        <a href="admin_dashboard.php" class="back-link">← Go To Dashboard</a>
    </div>

</body>

</html>