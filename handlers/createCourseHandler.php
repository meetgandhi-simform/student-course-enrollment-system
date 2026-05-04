<?php
session_start();

require_once __DIR__ . "./../Class/Course.php";
require_once __DIR__ . "./../helper/auth.php";
require_once __DIR__ . "./../validator/Validator.php";

requireLogin();
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $courseObj = new Course();

    $nameValidation = Validator::courseName($_POST['name']);
    $weeksValidation = Validator::weeks($_POST['weeks']);
    $seatsValidation = Validator::seats($_POST['seats']);

    if (!$nameValidation['status']) {
        throw new Exception($nameValidation['message']);
    }

    if (!$weeksValidation['status']) {
        throw new Exception($weeksValidation['message']);
    }

    if (!$seatsValidation['status']) {
        throw new Exception($seatsValidation['message']);
    }

    $name = $nameValidation['data'];
    $durationInWeeks = $weeksValidation['data'];
    $seats = $seatsValidation['data'];

    try {
        $result = $courseObj->createCourse($name, $durationInWeeks, $seats);
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = '/course-management/ui/admin_dashboard.php';
        </script>";
    }
    if ($result) {
        echo "<script>
            alert('Course Created Successfully');
            window.location.href = '/course-management/ui/createCourse.php';
        </script>";
    } else {
        echo "<script>
            alert('Error creating Course');
            window.location.href = '/course-management/ui/createCourse.php';
        </script>";
    }
}
