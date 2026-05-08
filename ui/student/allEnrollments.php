<?php
require __DIR__ . "./../../helper/AuthHelper.php";
session_start();
AuthHelper::requireRole('student');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.css">

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>

    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index.css">
    <title>All Enrollments</title>
</head>
<?php require_once 'navbar.php' ?>

<body>
    <div class="table-container">
        <h2>All Enrollments</h2>

        <table id="enrollmentTable" class="custom-table">

            <thead>
                <tr>
                    <th>Student Id</th>
                    <th>Course Name</th>
                    <th>Weeks</th>
                    <th>Instructor Id</th>
                    <th>Instructor Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody></tbody>

        </table>
        <script src="./../js/student/getAllEnrollments.js"></script>
</body>
<br /><br />

</html>