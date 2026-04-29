<?php
require_once __DIR__ . "./../../class/Course.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $instructor_id = $_POST['instructor_id'];
    $course_id = $_POST['course_id'];

    $course = new Course();

    $result = $course->assignInstructor($instructor_id, $course_id);

    if (is_array($result) && $result['status'] == false) {
        echo "<script>
            alert(" . json_encode($result['message']) . ");
            window.history.back();
        </script>";
    } else {
        echo "<script>
            alert('Instructor Assigned to course Succesfully ')
            window.location.href = '/course-management/ui/admin/courseWithInstructor.php?tab=courses';
        </script>";
        exit();
    }
}
