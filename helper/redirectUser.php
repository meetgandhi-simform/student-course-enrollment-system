<?php
function redirectUser($role)
{
    switch ($role) {
        case 'admin':
            header("Location: /course-management/ui/admin_dashboard.php");
            break;
        case 'student':
            header("Location: /course-management/ui/student_dashboard.php");
            break;
        case 'instructor':
            header("Location: /course-management/ui/instructor_dashboard.php");
            break;
            // default:
            //     header("Location: /course-management/ui/login.php");
    }
    exit();
}
