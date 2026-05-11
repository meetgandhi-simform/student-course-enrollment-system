<?php
session_start();
require_once __DIR__ . "./../../helper/AuthHelper.php";
AuthHelper::requireRole('admin');
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
    <link rel="stylesheet" href="./../css/index.css">
    <title>All Instructors</title>
</head>

<?php require 'navbar.php'; ?>

<body>
    <h2>All Instructors</h2>

    <table id="instructorTable" class="custom-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
    <script src="./../js/dataTableHelper.js"></script>
    <script src="./../js/admin/getAllInstructors.js"></script>
</body>

</html>