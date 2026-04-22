<?php

function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /course-management/ui/login.php");
        exit();
    }
}

function requireRole($role)
{
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) != strtolower($role)) {
        header("Location: /course-management/ui/login.php");
        exit();
    }
}
