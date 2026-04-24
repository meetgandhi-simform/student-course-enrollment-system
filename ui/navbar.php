<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo"><a href="admin_dashboard.php">Admin Dashboard</a></div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="alllStudents.php?tab=students">All Students</a></li>
            <li><a href="allInstructor.php?tab=instructors">All Instructors</a></li>
            <li><a href="allAdmin.php?tab=admins">All Admins</a></li>
            <li><a href="allEnrollments.php?tab=enrollments">All Enrollments</a></li>
            <li><a href="courseWithInstructor.php?tab=courses">All Courses</a></li>
            <li><a href="admin_create_user.php">Create User</a></li>
            <li><a href="createCourse.php">Create Course</a></li>
            <li><a href="enroll_student.php">Enroll Student</a></li>
            <li><a href="assignInstructor.php">Assign Instructor</a></li>


            <li class="profile-dropdown">
                <button onclick="toggleDropdown()">👤 Profile ▾</button>

                <ul id="dropdownMenu" class="dropdown-menu">
                    <li><a href="/course-management/ui/edit_profile.php">Edit Profile</a></li>
                    <li><a href="/course-management/auth/Logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</body>
<script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("show");
    }

    window.onclick = function(e) {
        if (!e.target.matches('button')) {
            let dropdown = document.getElementById("dropdownMenu");
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
</script>

</html>