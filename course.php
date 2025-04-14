<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: courses.php");
    exit;
}

$course_id = $_GET['id'];

// Get course + instructor info
$query = "SELECT c.*, i.name AS instructor_name, i.email AS instructor_email, i.bio AS instructor_bio, i.photo AS instructor_photo
          FROM courses c
          LEFT JOIN instructors i ON c.instructor_id = i.id
          WHERE c.id = $course_id";
$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<p>Course not found.</p>";
    exit;
}

$course = mysqli_fetch_assoc($result);

// Get lessons for the course
$lessons_query = "SELECT * FROM lessons WHERE course_id = $course_id ORDER BY id ASC";
$lessons_result = mysqli_query($connect, $lessons_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($course['title']); ?> | Course Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap CSS + FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold fs-4" href="index.php">E-Learning</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav gap-3">
        <li class="nav-item">
          <a class="nav-link nav-custom" href="index.php">Home</a>
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
<section class="hero-section py-5 text-center bg-light">
  <div class="container">
    <h1 class="display-4 fw-bold"><?php echo htmlspecialchars($course['title']); ?></h1>
    <p class="lead"><?php echo htmlspecialchars($course['category']); ?></p>
  </div>
</section>

<!-- Course Info Box -->
<section class="course-info-box container my-5 p-4 rounded shadow-sm">
  <div class="row">
    <div class="col-md-4">
      <img src="images/<?php echo htmlspecialchars($course['image']); ?>" alt="Course Image" class="img-fluid rounded mb-4">
    </div>
    <div class="col-md-8">
      <h2 class="course-title mb-3"><?php echo htmlspecialchars($course['title']); ?></h2>
      <p class="course-description mb-4"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

      <div class="row">
        <div class="col-md-4">
          <strong><i class="fas fa-tag me-2"></i>Category:</strong> 
          <span class="text-muted"><?php echo htmlspecialchars($course['category']); ?></span>
        </div>
        <div class="col-md-4">
          <strong><i class="fas fa-calendar-alt me-2"></i>Created At:</strong> 
          <span class="text-muted"><?php echo date('F j, Y', strtotime($course['created_at'])); ?></span>
        </div>
        <div class="col-md-4">
          <strong><i class="fas fa-play-circle me-2"></i>Total Lessons:</strong> 
          <span class="text-muted"><?php echo mysqli_num_rows($lessons_result); ?></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Course Details -->
<div class="container my-5">
  <div class="row g-5">
    <!-- Left Column -->
    <div class="col-md-12">
      <!-- Instructor Info -->
      <div class="mt-5 p-4 bg-light rounded shadow-sm">
        <h4 class="mb-3">Instructor</h4>
        <div class="d-flex align-items-center">
          <?php if (!empty($course['instructor_photo']) && file_exists('images/' . $course['instructor_photo'])): ?>
            <img src="images/<?php echo htmlspecialchars($course['instructor_photo']); ?>" class="rounded-circle me-3" width="80" height="80" alt="Instructor Photo">
          <?php else: ?>
            <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white me-3" style="width: 80px; height: 80px;">
              <i class="fas fa-user fa-2x"></i>
            </div>
          <?php endif; ?>
          <div>
            <h5 class="mb-1"><?php echo htmlspecialchars($course['instructor_name']); ?></h5>
            <p class="mb-0"><i class="fas fa-envelope me-1"></i> <a href="mailto:<?php echo htmlspecialchars($course['instructor_email']); ?>"><?php echo htmlspecialchars($course['instructor_email']); ?></a></p>
          </div>
        </div>
        <p><strong>Instructor Bio:</strong> <?php echo substr(strip_tags($course['instructor_bio']), 0, 100); ?>...</p>
      </div>

      <!-- Lessons -->
      <h3 class="mt-5">Lessons</h3>
      <?php if (mysqli_num_rows($lessons_result) > 0): ?>
        <div class="accordion" id="lessonsAccordion">
          <?php $index = 1; while ($lesson = mysqli_fetch_assoc($lessons_result)): ?>
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false">
                  <?php echo htmlspecialchars($lesson['title']); ?> 
                  <span class="ms-auto badge bg-primary"><?php echo htmlspecialchars($lesson['duration']); ?> mins</span>
                </button>
              </h2>
              <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" data-bs-parent="#lessonsAccordion">
                <div class="accordion-body">
                <p><?php echo strip_tags($lesson['content']); ?></p>
                  <?php if (!empty($lesson['video_url'])): ?>
                    <a href="<?php echo htmlspecialchars($lesson['video_url']); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                      <i class="fas fa-play"></i> Watch Lesson
                    </a>
                  <?php else: ?>
                    <p class="text-muted mb-0"><i>No video available</i></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php $index++; endwhile; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-info mt-3">No lessons found for this course.</div>
      <?php endif; ?>
    </div>
  </div>
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
