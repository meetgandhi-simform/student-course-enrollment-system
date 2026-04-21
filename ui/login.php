<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Login</title>

    <link rel="stylesheet" href="./css/index.css">
</head>

<body>

    <div class="auth-container">
        <h2>User Login</h2>

        <form method="POST" action="/course-management/handlers/loginHandler.php">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>

            <p class="auth-link">
                Don't have an account? <a href="register.php">Register</a>
            </p>

        </form>
    </div>

</body>

</html>