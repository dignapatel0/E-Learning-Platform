<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_GET['delete'])) {
    $query = 'DELETE FROM enrollments 
              WHERE id = '.$_GET['delete'].' 
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

<h2>Manage Enrollments</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Course</th>
            <th>Enrolled At</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php while($enrollment = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $enrollment['id']; ?></td>
            <td><?php echo $enrollment['user_email']; ?></td>
            <td><?php echo $enrollment['course_title']; ?></td>
            <td><?php echo date('M j, Y g:i a', strtotime($enrollment['enrolled_at'])); ?></td>
            <td><?php echo $enrollment['completed'] === 'Yes' ? 'Completed' : 'In Progress'; ?></td>
            <td>
                <a href="enrollments_edit.php?id=<?php echo $enrollment['id']; ?>" class="btn btn-primary">Edit</a>
                <a href="enrollments.php?delete=<?php echo $enrollment['id']; ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Are you sure you want to delete this enrollment?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="enrollments_add.php" class="btn btn-success">Add New Enrollment</a>

<?php
include('includes/footer.php');
?>