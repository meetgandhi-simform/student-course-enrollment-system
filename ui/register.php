<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>

    <link rel="stylesheet" href="./css/index.css">
</head>

<body>

    <div class="auth-container">
        <h2>Student Registration</h2>

        <form method="POST" action="/course-management/auth/Register.php">

            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" required>

            <button type="submit">Register</button>

            <p class="auth-link">
                Already have an account? <a href="login.php">Login</a>
            </p>

        </form>
    </div>

</body>

</html>