<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (isset($_GET['delete'])) {
    $query = 'DELETE FROM enrollments 
              WHERE id = ' . $_GET['delete'] . ' 
              LIMIT 1';
    mysqli_query($connect, $query);
    
    set_message('Enrollment has been deleted');
    header('Location: enrollments.php');
    die();
}

$query = 'SELECT e.*, u.email as user_email, c.title as course_title
          FROM enrollments e
          JOIN users u ON e.user_id = u.id
          JOIN courses c ON e.course_id = c.id
          ORDER BY e.enrolled_at DESC';
$result = mysqli_query($connect, $query);

include('includes/header.php');
?>

<div class="container">
    <h2 class="my-4 text-center">Manage Enrollments</h2>

    <div class="mb-3">
        <a href="enrollments_add.php" class="btn-add">
            <i class="fas fa-plus-square"></i> Add New Enrollment
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th width="80">ID</th>
                    <th>User</th>
                    <th>Course</th>
                    <th width="180">Enrolled At</th>
                    <th width="120">Status</th>
                    <th width="250">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($enrollment = mysqli_fetch_assoc($result)): ?>
                    <tr class="enrollment-row">
                        <td class="text-center"><?= (int)$enrollment['id']; ?></td>
                        <td><?= htmlentities($enrollment['user_email']); ?></td>
                        <td><?= htmlentities($enrollment['course_title']); ?></td>
                        <td><?= date('M j, Y g:i a', strtotime($enrollment['enrolled_at'])); ?></td>
                        <td class="text-center"><?= $enrollment['completed'] === 'Yes' ? 'Completed' : 'In Progress'; ?></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="enrollments_edit.php?id=<?= (int)$enrollment['id']; ?>" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="enrollments.php?delete=<?= (int)$enrollment['id']; ?>" 
                                   class="btn btn-outline-danger" 
                                   onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
