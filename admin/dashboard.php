<?php
// Start session and verify admin status

include('includes/config.php');
include('includes/functions.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: ../login.php');
    exit();
}

// Verify admin status (using email in your case)
$user_email = $_SESSION['user_email'] ?? '';
if ($user_email !== 'admin@gmail.com') {
    header('Location: ../index.php');
    exit();
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
    <title>Admin Dashboard | <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stat-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .admin-nav .nav-link {
            border-radius: 0;
        }
        .admin-nav .nav-link.active {
            border-left: 3px solid #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }
    </style>
</head>
<body>
    <?php include('includes/header.php');
 ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column admin-nav">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="courses.php">
                                <i class="fas fa-book me-2"></i> Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="lessons.php">
                                <i class="fas fa-list-ol me-2"></i> Lessons
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="instructors.php">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Instructors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="users.php">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="enrollments.php">
                                <i class="fas fa-clipboard-list me-2"></i> Enrollments
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2">Admin Dashboard</h1>
                <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['user_first'] ?? 'Admin'); ?></p>
                
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