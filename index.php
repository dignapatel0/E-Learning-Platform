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
  <link href="styles.css" type="text/css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="index.php"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="courses.php">Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="instructors.php">Instructors</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <div class="row">
      <div class="col-lg-8">
        <h1 class="display-4 mb-4">Welcome to Our E-Learning Platform</h1>
        <p class="lead">Expand your knowledge with our expert-led courses</p>
        
        <div class="row">
          <?php
          // Get featured courses
          $query = 'SELECT courses.*, instructors.name as instructor_name 
                    FROM courses 
                    LEFT JOIN instructors ON courses.instructor_id = instructors.id
                    ORDER BY created_at DESC LIMIT 3';
          $result = mysqli_query($connect, $query);
          
          while($course = mysqli_fetch_assoc($result)):
          ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <?php if($course['image']): ?>
                <img src="<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
              <?php else: ?>
                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 150px;">
                  <i class="fas fa-book fa-3x"></i>
                </div>
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                <p class="card-text text-muted">
                  <small>By <?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown Instructor'); ?></small>
                </p>
                <p class="card-text"><?php echo substr(htmlspecialchars($course['description']), 0, 100); ?>...</p>
              </div>
              <div class="card-footer bg-transparent">
                <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">View Course</a>
              </div>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
        
        <div class="text-center mt-4">
          <a href="courses.php" class="btn btn-outline-primary">View All Courses</a>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Top Instructors</h5>
          </div>
          <div class="card-body">
            <?php
            $query = 'SELECT * FROM instructors ORDER BY RAND() LIMIT 3';
            $result = mysqli_query($connect, $query);
            
            while($instructor = mysqli_fetch_assoc($result)):
            ?>
            <div class="d-flex mb-3">
              <?php if($instructor['photo']): ?>
                <img src="<?php echo $instructor['photo']; ?>" class="rounded-circle me-3" width="64" height="64" alt="<?php echo htmlspecialchars($instructor['name']); ?>">
              <?php else: ?>
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 64px; height: 64px;">
                  <i class="fas fa-user fa-2x"></i>
                </div>
              <?php endif; ?>
              <div>
                <h6 class="mb-1"><?php echo htmlspecialchars($instructor['name']); ?></h6>
                <p class="text-muted small mb-1"><?php echo substr(htmlspecialchars($instructor['bio']), 0, 50); ?>...</p>
                <a href="instructor.php?id=<?php echo $instructor['id']; ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
        
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Recent Lessons</h5>
          </div>
          <div class="list-group list-group-flush">
            <?php
            $query = 'SELECT lessons.*, courses.title as course_title 
                      FROM lessons 
                      JOIN courses ON lessons.course_id = courses.id
                      ORDER BY lessons.id DESC LIMIT 5';
            $result = mysqli_query($connect, $query);
            
            while($lesson = mysqli_fetch_assoc($result)):
            ?>
            <a href="lesson.php?id=<?php echo $lesson['id']; ?>" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1"><?php echo htmlspecialchars($lesson['title']); ?></h6>
                <small><?php echo $lesson['duration']; ?> min</small>
              </div>
              <small class="text-muted"><?php echo htmlspecialchars($lesson['course_title']); ?></small>
            </a>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>About Our Platform</h5>
          <p>We provide high-quality online courses taught by industry experts.</p>
        </div>
        <div class="col-md-3">
          <h5>Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="courses.php" class="text-white">All Courses</a></li>
            <li><a href="instructors.php" class="text-white">Instructors</a></li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5>Connect With Us</h5>
          <div class="social-links">
            <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-2"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
      <hr>
      <div class="text-center">
        <p class="mb-0">&copy; <?php echo date('Y'); ?>. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>