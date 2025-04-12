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
  <title><?php echo SITE_NAME; ?> - E-Learning Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="admin/styles.css">
  <style>
    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/hero-bg.jpg');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 100px 0;
    }
    .category-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 12px;
    }
    .instructor-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border: 3px solid #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .lesson-item:hover {
      background-color: #f8f9fa;
    }
    .stat-number {
      font-size: 2.5rem;
      font-weight: bold;
      color: white;
    }
    .navbar {
      background-color: #212529 !important;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php"><?php echo SITE_NAME; ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="courses.php">Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="instructors.php">Instructors</a>
          </li>
          <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i> 
                <?php echo htmlspecialchars($_SESSION['user_first'] . ' ' . htmlspecialchars($_SESSION['user_last'])); ?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="admin/dashboard.php">Dashboard</a></li>
                <li><a class="dropdown-item" href="my-courses.php">My Courses</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="admin/logout.php">Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section text-center">
    <div class="container">
      <h1 class="display-4 fw-bold mb-4">Expand Your Knowledge</h1>
      <p class="lead mb-5">Learn from industry experts with our comprehensive online courses</p>
      <a href="courses.php" class="btn btn-primary btn-lg px-4 me-2">Browse Courses</a>
      <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="register.php" class="btn btn-outline-light btn-lg px-4">Join Now</a>
      <?php endif; ?>
    </div>
  </section>

  <!-- Stats Counter -->
  <div class="container">
    <div class="stats-counter">
      <div class="row text-center">
        <?php
        $courses_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM courses'));
        $lessons_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM lessons'));
        $instructors_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM instructors'));
        $users_count = mysqli_num_rows(mysqli_query($connect, 'SELECT * FROM users WHERE active="Yes"'));
        ?>
        <div class="col-md-3">
          <div class="stat-number"><?php echo $courses_count; ?></div>
          <p>Courses</p>
        </div>
        <div class="col-md-3">
          <div class="stat-number"><?php echo $lessons_count; ?></div>
          <p>Lessons</p>
        </div>
        <div class="col-md-3">
          <div class="stat-number"><?php echo $instructors_count; ?></div>
          <p>Instructors</p>
        </div>
        <div class="col-md-3">
          <div class="stat-number"><?php echo $users_count; ?></div>
          <p>Students</p>
        </div>
      </div>
    </div>
  </div>

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
        // Check enrollment status for each course
        $enrolled = false;
        if(isset($_SESSION['user_id'])) {
            $enrollment_check = mysqli_query($connect, 
                "SELECT id FROM enrollments 
                 WHERE user_id = {$_SESSION['user_id']} 
                 AND course_id = {$course['id']}"
            );
            $enrolled = mysqli_num_rows($enrollment_check) > 0;
        }
      ?>
      <div class="col-md-6 col-lg-3">
        <div class="card h-100">
          <!-- ... (keep your existing card content) ... -->
          <div class="position-relative">
            <?php if($course['image']): ?>
              <img src="<?php echo htmlspecialchars($course['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>" style="height: 180px; object-fit: cover;">
            <?php else: ?>
              <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                <i class="fas fa-book fa-3x"></i>
              </div>
            <?php endif; ?>
            <?php if($course['category']): ?>
              <span class="category-badge"><?php echo htmlspecialchars($course['category']); ?></span>
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
            <div class="d-flex justify-content-between align-items-center">
              <a href="course.php?id=<?php echo $course['id']; ?>" 
                 class="btn btn-sm btn-outline-primary me-2">
                View Course
              </a>
              
              <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($enrolled): ?>
                  <span class="badge bg-success">Enrolled</span>
                <?php else: ?>
                  <a href="enroll.php?course_id=<?php echo $course['id']; ?>" 
                     class="btn btn-sm btn-primary">
                    Enroll Now
                  </a>
                <?php endif; ?>
              <?php else: ?>
                <a href="login.php?redirect=<?php echo urlencode("course.php?id={$course['id']}"); ?>" 
                   class="btn btn-sm btn-primary">
                  Enroll Now
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Popular Instructors -->
  <section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Popular Instructors</h2>
      <a href="instructors.php" class="btn btn-outline-primary">View All</a>
    </div>
    
    <div class="row g-4">
      <?php
      $query = 'SELECT * FROM instructors ORDER BY RAND() LIMIT 4';
      $result = mysqli_query($connect, $query);
      
      while($instructor = mysqli_fetch_assoc($result)):
      ?>
      <div class="col-md-6 col-lg-3">
        <div class="card text-center h-100">
          <div class="card-body">
            <div class="mx-auto mb-3">
              <?php if($instructor['photo']): ?>
                <img src="<?php echo htmlspecialchars($instructor['photo']); ?>" class="rounded-circle instructor-img" alt="<?php echo htmlspecialchars($instructor['name']); ?>">
              <?php else: ?>
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px;">
                  <i class="fas fa-user fa-2x"></i>
                </div>
              <?php endif; ?>
            </div>
            <h5 class="card-title mb-1"><?php echo htmlspecialchars($instructor['name']); ?></h5>
            <p class="text-muted small mb-3"><?php echo substr(htmlspecialchars($instructor['bio'] ?? ''), 0, 50); ?>...</p>
            <a href="instructor.php?id=<?php echo $instructor['id']; ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="bg-primary text-white py-5">
    <div class="container text-center">
      <h2 class="fw-bold mb-4">Ready to Start Learning?</h2>
      <p class="lead mb-4">Join thousands of students advancing their careers with our courses</p>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="courses.php" class="btn btn-light btn-lg px-4 me-2">Browse Courses</a>
      <?php else: ?>
        <a href="account/register.php" class="btn btn-light btn-lg px-4 me-2">Sign Up Free</a>
        <a href="courses.php" class="btn btn-outline-light btn-lg px-4">Browse Courses</a>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
          <h5 class="fw-bold mb-3"><?php echo SITE_NAME; ?></h5>
          <p>We provide high-quality online courses taught by industry experts to help you advance your career.</p>
          <div class="social-links mt-3">
            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
          <h5 class="fw-bold mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="index.php" class="text-white">Home</a></li>
            <li class="mb-2"><a href="courses.php" class="text-white">Courses</a></li>
            <li class="mb-2"><a href="instructors.php" class="text-white">Instructors</a></li>
            <li class="mb-2"><a href="account/login.php" class="text-white">Login</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
          <h5 class="fw-bold mb-3">Categories</h5>
          <ul class="list-unstyled">
            <?php
            $query = 'SELECT DISTINCT category FROM courses WHERE category IS NOT NULL LIMIT 5';
            $result = mysqli_query($connect, $query);
            
            while($category = mysqli_fetch_assoc($result)):
            ?>
            <li class="mb-2"><a href="courses.php?category=<?php echo urlencode($category['category']); ?>" class="text-white"><?php echo htmlspecialchars($category['category']); ?></a></li>
            <?php endwhile; ?>
          </ul>
        </div>
        
      </div>
      <hr class="my-4">
      <div class="row">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <p class="mb-0">
            <a href="#" class="text-white me-3">Privacy Policy</a>
            <a href="#" class="text-white me-3">Terms of Service</a>
            <a href="#" class="text-white">Contact Us</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>