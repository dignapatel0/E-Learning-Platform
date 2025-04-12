<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Courses | <?php echo SITE_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .course-card {
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .course-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .lesson-count {
      background-color: #f8f9fa;
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>
  <?php include('admin/includes/header.php'); ?>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h1 class="fw-bold">All Courses</h1>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          Filter by Category
        </button>
        <ul class="dropdown-menu">
          <?php
          $categories = mysqli_query($connect, "SELECT DISTINCT category FROM courses WHERE category IS NOT NULL");
          while($cat = mysqli_fetch_assoc($categories)): ?>
            <li><a class="dropdown-item" href="?category=<?php echo urlencode($cat['category']); ?>">
              <?php echo htmlspecialchars($cat['category']); ?>
            </a></li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>

    <div class="row g-4">
      <?php
      $category_filter = isset($_GET['category']) ? "AND category = '".mysqli_real_escape_string($connect, $_GET['category'])."'" : "";
      
      $query = "SELECT c.*, i.name as instructor_name, 
                (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
                FROM courses c
                LEFT JOIN instructors i ON c.instructor_id = i.id
                WHERE 1=1 $category_filter
                ORDER BY c.created_at DESC";
      
      $result = mysqli_query($connect, $query);
      
      if(mysqli_num_rows($result) > 0):
        while($course = mysqli_fetch_assoc($result)):
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
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 course-card">
          <div class="position-relative">
            <?php if($course['image']): ?>
              <img src="<?php echo htmlspecialchars($course['image']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
            <?php else: ?>
              <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                <i class="fas fa-book fa-3x"></i>
              </div>
            <?php endif; ?>
            <span class="position-absolute top-0 end-0 m-2 lesson-count">
              <i class="fas fa-list-ol me-1"></i> <?php echo $course['lesson_count']; ?> lessons
            </span>
          </div>
          
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
            <p class="card-text text-muted small mb-2">
              <i class="fas fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown Instructor'); ?>
            </p>
            <p class="card-text"><?php echo substr(display_html_content($course['description'] ?? ''), 0, 100); ?>...</p>
          </div>
          
          <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
              <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-outline-primary">
                View Details
              </a>
              <?php if($enrolled): ?>
                <span class="badge bg-success">Enrolled</span>
              <?php else: ?>
                <a href="<?php echo isset($_SESSION['user_id']) ? 'enroll.php?course_id='.$course['id'] : 'login.php?redirect='.urlencode('course.php?id='.$course['id']); ?>" 
                   class="btn btn-sm btn-primary">
                  Enroll Now
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php 
        endwhile;
      else: ?>
      <div class="col-12">
        <div class="alert alert-info">No courses found. Please check back later.</div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include('admin/includes/footer.php'); ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>