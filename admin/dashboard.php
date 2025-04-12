<?php
// Start session and verify admin status
include('includes/config.php');
include('includes/functions.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
include('includes/database.php');

// Get statistics for dashboard
$stats = [
    'courses' => mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as count FROM courses"))['count'],
    'lessons' => mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as count FROM lessons"))['count'],
    'instructors' => mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as count FROM instructors"))['count'],
    'users' => mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as count FROM users"))['count'],
    'enrollments' => mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as count FROM enrollments"))['count']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-Learning Platform</title>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Main content -->
            <main class="col-12 px-md-4 py-4">
                <h1 class="h2">Admin Dashboard</h1>                
                <div class="row my-4">
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Courses</h5>
                                <h2 class="mb-0"><?php echo $stats['courses']; ?></h2>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="courses.php" class="btn btn-sm btn-outline-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Lessons</h5>
                                <h2 class="mb-0"><?php echo $stats['lessons']; ?></h2>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="lessons.php" class="btn btn-sm btn-outline-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Instructors</h5>
                                <h2 class="mb-0"><?php echo $stats['instructors']; ?></h2>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="instructors.php" class="btn btn-sm btn-outline-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Users</h5>
                                <h2 class="mb-0"><?php echo $stats['users']; ?></h2>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="users.php" class="btn btn-sm btn-outline-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-muted">Enrollments</h5>
                                <h2 class="mb-0"><?php echo $stats['enrollments']; ?></h2>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="enrollments.php" class="btn btn-sm btn-outline-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('includes/footer.php'); ?>