<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate course ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: courses.php');
    exit();
}

$course_id = (int)$_GET['id'];

// Get course details
$query = "SELECT c.*, i.name as instructor_name, i.bio as instructor_bio, i.photo as instructor_photo
          FROM courses c
          LEFT JOIN instructors i ON c.instructor_id = i.id
          WHERE c.id = $course_id";
$result = mysqli_query($connect, $query);
$course = mysqli_fetch_assoc($result);

if (!$course) {
    header('Location: courses.php');
    exit();
}

// Check enrollment status
$enrolled = false;
if (isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $query = "SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = $course_id";
    $result = mysqli_query($connect, $query);
    $enrolled = mysqli_num_rows($result) > 0;
}

// Get lessons for this course
$lessons_query = "SELECT * FROM lessons 
                  WHERE course_id = $course_id 
                  ORDER BY sort_order ASC";
$lessons_result = mysqli_query($connect, $lessons_query);

// Calculate total duration
$total_duration = 0;
while ($lesson = mysqli_fetch_assoc($lessons_result)) {
    $total_duration += (int)$lesson['duration'];
}
mysqli_data_seek($lessons_result, 0); // Reset pointer
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($course['title']); ?> | <?php echo SITE_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .course-hero {
      background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo htmlspecialchars($course['image'] ?? 'assets/images/course-bg.jpg'); ?>');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 80px 0;
      margin-bottom: 30px;
    }
    .lesson-item {
      border-left: 3px solid #0d6efd;
      transition: all 0.3s;
    }
    .lesson-item:hover {
      background-color: #f8f9fa;
    }
    .locked-lesson {
      opacity: 0.6;
    }
  </style>
</head>
<body>
  <?php include('admin/includes/header.php'); ?>

  <!-- Course Hero Section -->
  <section class="course-hero mb-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="courses.php">Courses</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($course['title']); ?></li>
            </ol>
          </nav>
          <h1 class="display-4 fw-bold mb-3"><?php echo htmlspecialchars($course['title']); ?></h1>
          <p class="lead mb-4"><?php echo display_html_content($course['description']); ?></p>
          
          <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
            <span class="badge bg-primary">
              <i class="fas fa-book me-1"></i> 
              <?php echo mysqli_num_rows($lessons_result); ?> Lessons
            </span>
            <span class="badge bg-info">
              <i class="fas fa-clock me-1"></i> <?php echo $total_duration; ?> Minutes
            </span>
            <?php if($course['category']): ?>
              <span class="badge bg-secondary">
                <?php echo htmlspecialchars($course['category']); ?>
              </span>
            <?php endif; ?>
          </div>
          
          <?php if($enrolled): ?>
            <a href="lesson.php?id=<?php echo get_first_lesson_id($course_id); ?>" class="btn btn-success btn-lg">
              Start Learning
            </a>
          <?php else: ?>
            <a href="<?php echo isset($_SESSION['user_id']) ? 'enroll.php?course_id='.$course_id : 'login.php?redirect='.urlencode('course.php?id='.$course_id); ?>" 
               class="btn btn-primary btn-lg">
              Enroll Now
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <div class="container mb-5">
    <div class="row">
      <div class="col-lg-8">
        <!-- Course Content -->
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h3 class="mb-0">Course Content</h3>
          </div>
          <div class="card-body">
            <div class="list-group">
              <?php if(mysqli_num_rows($lessons_result) > 0): ?>
                <?php while($lesson = mysqli_fetch_assoc($lessons_result)): ?>
                  <?php if($enrolled): ?>
                    <a href="lesson.php?id=<?php echo $lesson['id']; ?>" class="list-group-item list-group-item-action lesson-item py-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h5 class="mb-1"><?php echo htmlspecialchars($lesson['title']); ?></h5>
                          <?php if($lesson['duration']): ?>
                            <small class="text-muted"><?php echo $lesson['duration']; ?> min</small>
                          <?php endif; ?>
                        </div>
                        <i class="fas fa-play-circle text-primary"></i>
                      </div>
                    </a>
                  <?php else: ?>
                    <div class="list-group-item lesson-item locked-lesson py-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h5 class="mb-1"><?php echo htmlspecialchars($lesson['title']); ?></h5>
                          <?php if($lesson['duration']): ?>
                            <small class="text-muted"><?php echo $lesson['duration']; ?> min</small>
                          <?php endif; ?>
                        </div>
                        <i class="fas fa-lock text-muted"></i>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endwhile; ?>
              <?php else: ?>
                <div class="list-group-item py-3">
                  <p class="mb-0 text-muted">No lessons available yet.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <!-- Course Description -->
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h3 class="mb-0">Description</h3>
          </div>
          <div class="card-body">
            <?php echo display_html_content($course['description']); ?>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <!-- Course Info Card -->
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h4 class="mb-0">Course Details</h4>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-book me-2"></i> Lessons</span>
                <span><?php echo mysqli_num_rows($lessons_result); ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clock me-2"></i> Duration</span>
                <span><?php echo $total_duration; ?> minutes</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-user-tie me-2"></i> Instructor</span>
                <span><?php echo htmlspecialchars($course['instructor_name'] ?? 'Unknown'); ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-layer-group me-2"></i> Category</span>
                <span><?php echo htmlspecialchars($course['category'] ?? 'Not specified'); ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="fas fa-calendar me-2"></i> Created</span>
                <span><?php echo date('M d, Y', strtotime($course['created_at'])); ?></span>
              </li>
            </ul>
            
            <?php if($enrolled): ?>
              <div class="d-grid mt-3">
                <a href="lesson.php?id=<?php echo get_first_lesson_id($course_id); ?>" class="btn btn-success">
                  Start Learning
                </a>
              </div>
            <?php else: ?>
              <div class="d-grid mt-3">
                <a href="<?php echo isset($_SESSION['user_id']) ? 'enroll.php?course_id='.$course_id : 'login.php?redirect='.urlencode('course.php?id='.$course_id); ?>" 
                   class="btn btn-primary">
                  Enroll Now
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        
        <!-- Instructor Card -->
        <?php if($course['instructor_name']): ?>
          <div class="card">
            <div class="card-header bg-white">
              <h4 class="mb-0">Instructor</h4>
            </div>
            <div class="card-body text-center">
              <?php if($course['instructor_photo']): ?>
                <img src="<?php echo htmlspecialchars($course['instructor_photo']); ?>" 
                     class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
              <?php endif; ?>
              <h5><?php echo htmlspecialchars($course['instructor_name']); ?></h5>
              <p class="text-muted small"><?php echo display_html_content($course['instructor_bio'] ?? ''); ?></p>
              <a href="instructor.php?id=<?php echo $course['instructor_id']; ?>" class="btn btn-outline-primary btn-sm">
                View Profile
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php include('admin/includes/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Helper function to get first lesson ID
function get_first_lesson_id($course_id) {
  global $connect;
  $query = "SELECT id FROM lessons WHERE course_id = $course_id ORDER BY sort_order ASC LIMIT 1";
  $result = mysqli_query($connect, $query);
  return mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result)['id'] : 0;
}