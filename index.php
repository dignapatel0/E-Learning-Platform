<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Learning Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top modern-dark-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="index.php">E-Learning</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav gap-3">
            <li class="nav-item">
            <a class="nav-link nav-custom active" href="index.php">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link nav-custom" href="courses.php">Courses</a>
            </li>
            <li class="nav-item">
            <a class="nav-link nav-custom" href="instructors.php">Instructors</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center py-5">
        <div class="container">
        <h1 class="display-4 fw-bold mb-4">Expand Your Knowledge</h1>
        <p class="lead">Learn from industry experts with our comprehensive online courses</p>
        <a href="courses.php" class="btn btn-primary btn-lg px-4 me-2">Browse Courses</a>
        </div>
    </section>

    <!-- Enroll Stats Section -->
    <section class="container my-5">
    <div class="row g-4 text-center enroll-stats-detailed">
    <?php
        $courses_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM courses'));
        $lessons_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM lessons'));
        $instructors_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM instructors'));
        ?>
        <div class="col-md-4">
        <div class="stat-card p-4 rounded shadow bg-light h-100">
            <div class="icon-wrapper bg-primary text-white mb-3">
            <i class="fas fa-book fa-2x"></i>
            </div>
            <h3 class="stat-number"><?php echo $courses_count; ?></h3>
            <p class="stat-title text-muted mb-1">Total Courses Available</p>
            <p class="stat-desc small">Browse a wide variety of topics and categories.</p>
        </div>
        </div>
        <div class="col-md-4">
        <div class="stat-card p-4 rounded shadow bg-light h-100">
            <div class="icon-wrapper bg-success text-white mb-3">
            <i class="fas fa-video fa-2x"></i>
            </div>
            <h3 class="stat-number"><?php echo $lessons_count; ?></h3>
            <p class="stat-title text-muted mb-1">Engaging Video Lessons</p>
            <p class="stat-desc small">Hands-on learning with interactive content.</p>
        </div>
        </div>
        <div class="col-md-4">
        <div class="stat-card p-4 rounded shadow bg-light h-100">
            <div class="icon-wrapper bg-warning text-white mb-3">
            <i class="fas fa-chalkboard-teacher fa-2x"></i>
            </div>
            <h3 class="stat-number"><?php echo $instructors_count; ?></h3>
            <p class="stat-title text-muted mb-1">Expert Instructors</p>
            <p class="stat-desc small">Learn from experienced professionals and mentors.</p>
        </div>
        </div>
    </div>
    </section>

    <!-- Featured Courses -->
    <section class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Featured Courses</h2>
        <a href="courses.php" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row g-4">
        <?php
        $query = 'SELECT c.*, i.name as instructor_name 
                    FROM courses c
                    LEFT JOIN instructors i ON c.instructor_id = i.id
                    ORDER BY c.created_at DESC LIMIT 4';
        $result = mysqli_query($connect, $query);
        
        while($course = mysqli_fetch_assoc($result)):
        ?>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
            <div class="position-relative">
                <?php if($course['image'] && file_exists('images/' . $course['image'])): ?>
                    <img src="images/<?php echo htmlspecialchars($course['image']); ?>" 
                        class="card-img-top" 
                        alt="<?php echo htmlspecialchars($course['title']); ?>" 
                        style="height: 180px; object-fit: cover;">
                    <?php else: ?>
                    <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-book fa-3x"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                <p class="card-text text-muted small mb-2">
                <i class="fas fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown Instructor'); ?>
                </p>
                <p class="card-text"><?php echo substr(htmlspecialchars($course['description'] ?? ''), 0, 80); ?>...</p>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-outline-primary">View Course</a>
            </div>
            </div>
        </div>
        <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <h5 class="fw-bold mb-3">About Us</h5>
                <p>We provide high-quality online courses taught by industry experts to help you advance your career.</p>
                <div class="social-links mt-3">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a> <!-- Added YouTube -->
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                <li class="mb-2"><a href="index.php" class="text-white">Home</a></li>
                <li class="mb-2"><a href="courses.php" class="text-white">Courses</a></li>
                <li class="mb-2"><a href="instructors.php" class="text-white">Instructors</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Contact Us</h5>
                <p>Have any questions or need support? Reach out to us!</p>
                <ul class="list-unstyled">
                <li><i class="fas fa-phone-alt"></i> (123) 456-7890</li>
                <li><i class="fas fa-envelope"></i> support@elearning.com</li>
                <li><i class="fas fa-map-marker-alt"></i> 1234 Education St, Learning City</li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Subscribe to Our Newsletter</h5>
                <p>Stay updated with the latest courses, news, and promotions by signing up for our newsletter!</p>
                <form action="#" method="POST">
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Your email" required>
                    <button class="btn btn-primary" type="submit">Subscribe</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
