<?php
session_start();

require_once "/var/www/html/course-management/helper/auth.php";

requireLogin();
requireRole('instructor');
?>