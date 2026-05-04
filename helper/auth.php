<?php

/**
 * Ensure user is logged in
 *
 * Redirects to login page if session is not set
 *
 * @return void
 */
function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /course-management/ui/login.php");
        exit();
    }
}

/**
 * Ensure user has required role
 *
 * @param string $role Required role (Admin, Student, etc.)
 * 
 * @return void
 */
function requireRole($role)
{
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) != strtolower($role)) {
        header("Location: /course-management/ui/login.php");
        exit();
    }
}
