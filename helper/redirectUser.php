<?php

/**
 * Redirect user based on role
 *
 * @param string $role User role (admin, student, instructor)
 * 
 * @return void
 */
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
            header("Location: /course-management/ui/instructor/instructorDashboard.php");
            break;
    }
    exit();
}
