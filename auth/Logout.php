<?php

session_start();

session_unset();
session_destroy();

header("Location: /course-management/ui/login.php");
exit();
