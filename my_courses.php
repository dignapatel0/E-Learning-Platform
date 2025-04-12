<?php
require_once 'admin/includes/database.php';
require_once 'admin/includes/config.php';
require_once 'admin/includes/functions.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get enrolled courses
$query = "SELECT courses.*, enrollments.enrolled_at, enrollments.completed
          FROM enrollments
          JOIN courses ON enrollments.course_id = courses.id
          WHERE enrollments.user_id = ?
          ORDER BY enrollments.enrolled_at DESC";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$courses = mysqli_stmt_get_result($stmt);

include('admin/includes/header.php');
?>

<div class="container mt-5">
    <h1>My Courses</h1>
    
    <?php if (mysqli_num_rows($courses) > 0): ?>
        <div class="row">
            <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($course['image'])): ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($course['title']) ?>"
                                 style="height: 180px; object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    Enrolled on <?= date('M j, Y', strtotime($course['enrolled_at'])) ?>
                                </small>
                            </p>
                            
                            <div class="progress mb-3">
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: <?= $course['completed'] == 'Yes' ? '100' : '25' ?>%" 
                                     aria-valuenow="<?= $course['completed'] == 'Yes' ? '100' : '25' ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?= $course['completed'] == 'Yes' ? 'Completed' : 'In Progress' ?>
                                </div>
                            </div>
                            
                            <a href="course.php?id=<?= $course['id'] ?>" class="btn btn-primary">
                                <?= $course['completed'] == 'Yes' ? 'Review Course' : 'Continue Learning' ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            You haven't enrolled in any courses yet.
            <a href="courses.php" class="alert-link">Browse courses</a> to get started!
        </div>
    <?php endif; ?>
</div>

<?php include('admin/includes/footer.php'); ?>