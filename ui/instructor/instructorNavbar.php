<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo"><a href="instructorDashboard.php">Instructor Dashboard</a></div>
        <ul class="nav-links">
            <li><a href="instructorDashboard.php">Dashboard</a></li>
            <li><a href="allEnrollments.php?tab=enrollments">All Enrollments</a></li>
            <li><a href="course.php?tab=courses">All Courses</a></li>
            <li><a href="enrollStudent.php">Enroll Student</a></li>


            <li class="profile-dropdown">
                <button onclick="toggleDropdown()">👤 Profile ▾</button>

                <ul id="dropdownMenu" class="dropdown-menu">
                    <li><a href="editProfile.php">Edit Profile</a></li>
                    <li><a href="/course-management/auth/Logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</body>
<script src="./../ui/js/navbar.js"></script>

</html>