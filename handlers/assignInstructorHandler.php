<?php
require_once "/var/www/html/course-management/Class/Course.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $instructor_id = $_POST['instructor_id'];
    $course_id = $_POST['course_id'];

    $course = new Course();

    $result = $course->assignInstructor($instructor_id, $course_id);

    if (is_array($result) && $result['status'] == false) {
        echo "<script>
            alert(". json_encode($result['message']) . ");
            window.history.back();
        </script>";
    } else {
        header("Location: /course-management/ui/admin_dashboard.php");
        exit();
    }
}
?>
