<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <div class="layout">

        <aside class="sidebar">

            <div class="logo">
                <a href="adminDashboard.php">Admin Panel</a>
            </div>

            <ul class="menu">
                <li><a href="adminDashboard.php">Dashboard</a></li>
                <li><a href="alllStudents.php?tab=students">All Students</a></li>
                <li><a href="allInstructor.php?tab=instructors">All Instructors</a></li>
                <li><a href="allAdmin.php?tab=admins">All Admins</a></li>
                <li><a href="allEnrollments.php?tab=enrollments">All Enrollments</a></li>
                <li><a href="courseWithInstructor.php?tab=courses">All Courses</a></li>

                <li class="menu-title">Management</li>
                <li><a href="adminCreateUser.php">Create User</a></li>
                <li><a href="createCourse.php">Create Course</a></li>
                <li><a href="enrollStudent.php">Enroll Student</a></li>
                <li><a href="assignInstructor.php">Assign Instructor</a></li>
                <li class="menu-title">Account</li>

                <li><a href="editProfile.php">Edit Profile</a></li>
                <li><a href="/course-management/auth/Logout.php" class="logout">Logout</a></li>
            </ul>

        </aside>

        <main class="main-content">
</body>
<script src="./../ui/js/navbar.js"></script>

</html>