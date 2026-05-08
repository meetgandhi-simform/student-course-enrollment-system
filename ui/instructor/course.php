<?php
require __DIR__ . "./../../helper/AuthHelper.php";
session_start();
AuthHelper::requireRole('instructor');
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
    <title>Courses</title>
</head>

<?php require_once 'navbar.php' ?>

<body>
    <div class="table-container">
        <h2>Courses</h2>

        <table id="courseTable" class="custom-table">

            <thead>
                <tr>
                    <th>Id</th>
                    <th>Course Name</th>
                    <th>Seats Left</th>
                </tr>
            </thead>

            <tbody></tbody>

        </table>
    </div>
    <script src="./../js/instructor/getAllCourses.js"></script>
</body>

</html>