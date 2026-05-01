<?php
require __DIR__ . "./../../handlers/adminHandlers/adminDashboardHandler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/index.css">
    <title>All Instructors</title>
</head>

<?php require 'navbar.php'; ?>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <div class="table-container">
        <h2>All Instructors</h2>

        <table class="custom-table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($instructors as $instructor): ?>
                <tr>
                    <td><?= $instructor['id']; ?></td>
                    <td><?= $instructor['name']; ?></td>
                    <td><?= $instructor['email']; ?></td>
                    <td><?= $instructor['phone']; ?></td>
                    <td><?= $instructor['role']; ?></td>
                    <td>
                        <span class="status <?= strtolower($instructor['isActive']) ?>">
                            <?= $instructor['isActive']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($instructor['isActive'] === 'Active'): ?>
                            <a class="delete-btn"
                                href="/course-management/auth/Delete.php?id=<?= $instructor['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                Delete
                            </a>
                        <?php endif; ?>

                        <?php if ($instructor['isActive'] === 'Inactive'): ?>
                            <a class="active-btn"
                                href="/course-management/handlers/adminInstructorHandlers/activeUserHandler.php?id=<?= $instructor['id']; ?>"
                                onclick="return confirm('Are You sure You want to activate this user? ')">Activate User
                            </a>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">

            <!-- Previous -->
            <?php if ($instructor_page > 1): ?>
                <a href="?tab=instructors&instructor_page=<?= $instructor_page - 1 ?>">&#11013;</a>
            <?php endif; ?>

            <!-- Numbers -->
            <?php for ($i = 1; $i <= $totalInstructorPages; $i++): ?>
                <a href="?tab=instructors&instructor_page=<?= $i ?>"
                    class="<?= ($i == $instructor_page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($instructor_page < $totalInstructorPages): ?>
                <a href="?tab=instructors&instructor_page=<?= $instructor_page + 1 ?>">&#10145;</a>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>