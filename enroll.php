<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

if (isset($_GET['course_id'])) {
    $course_id = (int)$_GET['course_id'];

    // Get course details
    $course_query = mysqli_query($connect, "
        SELECT c.*, i.name AS instructor_name 
        FROM courses c 
        LEFT JOIN instructors i ON c.instructor_id = i.id 
        WHERE c.id = $course_id
    ");
    $course = mysqli_fetch_assoc($course_query);
} else {
    header("Location: courses.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enrollment Confirmation</title>
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
          <li class="nav-item"><a class="nav-link nav-custom" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link nav-custom" href="courses.php">Courses</a></li>
          <li class="nav-item"><a class="nav-link nav-custom" href="instructors.php">Instructors</a></li>
        </ul>
      </div>
    </div>
  </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center bg-success text-white py-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3"><i class="fas fa-check-circle"></i> You're Enrolled!</h1>
        <p class="lead mb-4">You've successfully joined the course and can start learning right away.</p>
        <a href="course.php?id=<?php echo $course_id; ?>" class="btn btn-light btn-lg px-4 me-2">Start Course</a>
        <a href="courses.php" class="btn btn-outline-light btn-lg px-4">Browse More Courses</a>
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
        <div class="col-md-3"><div class="stat-number"><?php echo $courses_count; ?></div><p>Courses</p></div>
        <div class="col-md-3"><div class="stat-number"><?php echo $lessons_count; ?></div><p>Lessons</p></div>
        <div class="col-md-3"><div class="stat-number"><?php echo $instructors_count; ?></div><p>Instructors</p></div>
        <div class="col-md-3"><div class="stat-number"><?php echo $users_count; ?></div><p>Students</p></div>
      </div>
    </div>
  </div>

  <!-- Enrollment Confirmation -->
  <div class="container py-5 text-center">
    <h1 class="text-success"><i class="fas fa-check-circle"></i> Enrollment Successful!</h1>
    <p class="lead mt-4">You've successfully enrolled in:</p>
    <h2 class="fw-bold"><?php echo htmlspecialchars($course['title']); ?></h2>
    <p class="text-muted">Instructor: <?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown'); ?></p>
    <a href="course.php?id=<?php echo $course_id; ?>" class="btn btn-primary mt-3">Start Course</a>
    <a href="courses.php" class="btn btn-outline-secondary mt-3">Back to Courses</a>
  </div>

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
            <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
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
