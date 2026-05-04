<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Login</title>

    <link rel="stylesheet" href="/course-management/ui/css/index.css">
</head>

<body>

    <div class="auth-container">
        <h2>User Login</h2>

        <form id="loginForm" method="POST">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="captchaInput">Captcha</label>

            <div id="captchaBox">
                <img src="/course-management/handlers/captchaHandler.php" id="captcha_img" alt="Captcha">
                <button type="button" id="btn">Refresh</button>
            </div>

            <input type="text" name="captcha" id="captchaInput" placeholder="Enter Captcha" required>

            <button type="submit">Login</button>

            <p class="auth-link">
                Don't have an account? <a href="/course-management/ui/register.php">Register</a>
            </p>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/captcha.js"></script>
    <script src="./js/login.js"></script>

</body>

</html>