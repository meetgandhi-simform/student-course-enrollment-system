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
 * Ensure the authenticated user has one of the required roles.
 *
 * Redirects to login page if:
 * - User is not logged in
 * - User role is not in the allowed roles list
 *
 * @param string|array $roles One or more allowed roles (e.g., 'admin' or ['admin', 'instructor'])
 *
 * @return void
 */
function requireRole($roles)
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: /course-management/ui/login.php");
        exit();
    }

    $roles = (array) $roles;

    $userRole = strtolower($_SESSION['role']);
    $allowedRoles = array_map('strtolower', $roles);

    if (!in_array($userRole, $allowedRoles)) {
        header("Location: /course-management/ui/login.php");
        exit();
    }
}
