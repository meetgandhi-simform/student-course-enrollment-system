<?php

require __DIR__ . "./../handlers/instructorDashboardHandler.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
</head>

<body>
    <h2>Instructor Dashboard</h2>
    <p>Welcome <?php echo $_SESSION['name']; ?> Your <?php echo $_SESSION['role']; ?> Id is <?php echo $_SESSION['user_id']; ?></p>

    <a href="/course-management/auth/Logout.php">Logout</a>
</body>

</html>