<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    // First delete the image if it exists
    $query = 'SELECT image FROM courses WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['delete']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $image = mysqli_fetch_assoc($result)['image'];
    
    if ($image && file_exists($image)) {
        unlink($image);
    }
    
    // Then delete the course
    $query = 'DELETE FROM courses WHERE id = ? LIMIT 1';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['delete']);
    mysqli_stmt_execute($stmt);
    
    set_message('Course has been deleted');
    header('Location: ' . ADMIN_URL . 'courses.php');
    die();
}

include('includes/header.php');

$query = 'SELECT courses.*, instructors.name as instructor_name
          FROM courses
          LEFT JOIN instructors ON courses.instructor_id = instructors.id
          ORDER BY title ASC';
$result = mysqli_query($connect, $query);
?>

<div class="container">
    <h2 class="my-4 text-center">Manage Courses</h2>
    
    <div class="mb-3">
        <a href="courses_add.php" class="btn-add">
        <i class="fas fa-plus-square"></i> Add New Course
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th width="120">Image</th>
                    <th width="80">ID</th>
                    <th>Title</th>
                    <th width="150">Instructor</th>
                    <th width="120">Category</th>
                    <th width="280">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($record = mysqli_fetch_assoc($result)): ?>
                    <tr class="course-row">
                        <td class="text-center">
                            <?php if (!empty($record['image']) && file_exists($record['image'])): ?>
                                <img src="<?= htmlspecialchars($record['image']) ?>" 
                                     class="img-thumbnail course-img"
                                     alt="<?= htmlspecialchars($record['title']) ?>">
                            <?php else: ?>
                                <div class="text-muted">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= (int)$record['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($record['title']) ?></strong>
                            <div class="text-muted small">
                                <?= htmlspecialchars(substr($record['description'], 0, 100)) ?>
                                <?= strlen($record['description']) > 100 ? '...' : '' ?>
                            </div>
                        </td>
                        <td><?= !empty($record['instructor_name']) ? htmlspecialchars($record['instructor_name']) : 'N/A' ?></td>
                        <td><?= !empty($record['category']) ? htmlspecialchars($record['category']) : 'N/A' ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="lessons.php?course_id=<?= (int)$record['id'] ?>" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-book-open"></i> Lessons
                                </a>
                                <a href="courses_photo.php?id=<?= (int)$record['id'] ?>" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-camera"></i> Photo
                                </a>
                                <a href="courses_edit.php?id=<?= (int)$record['id'] ?>" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="courses.php?delete=<?= (int)$record['id'] ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Delete this course?')">
                                    <i class="fas fa-trash-alt"></i>
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

