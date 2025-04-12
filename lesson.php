<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate lesson ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: courses.php');
    exit();
}

$lesson_id = (int)$_GET['id'];

// Get lesson details with course and instructor info
$query = "SELECT l.*, c.title as course_title, c.id as course_id, 
          i.name as instructor_name, i.photo as instructor_photo
          FROM lessons l
          JOIN courses c ON l.course_id = c.id
          LEFT JOIN instructors i ON c.instructor_id = i.id
          WHERE l.id = $lesson_id";
$result = mysqli_query($connect, $query);
$lesson = mysqli_fetch_assoc($result);

if (!$lesson) {
    header('Location: courses.php');
    exit();
}

// Check enrollment status
$enrolled = false;
if (isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $query = "SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = {$lesson['course_id']}";
    $result = mysqli_query($connect, $query);
    $enrolled = mysqli_num_rows($result) > 0;
}

// Redirect if not enrolled
if (!$enrolled) {
    header("Location: course.php?id={$lesson['course_id']}");
    exit();
}

// Get adjacent lessons for navigation
$prev_query = "SELECT id, title FROM lessons 
              WHERE course_id = {$lesson['course_id']} AND sort_order < {$lesson['sort_order']} 
              ORDER BY sort_order DESC LIMIT 1";
$prev_lesson = mysqli_fetch_assoc(mysqli_query($connect, $prev_query));

$next_query = "SELECT id, title FROM lessons 
              WHERE course_id = {$lesson['course_id']} AND sort_order > {$lesson['sort_order']} 
              ORDER BY sort_order ASC LIMIT 1";
$next_lesson = mysqli_fetch_assoc(mysqli_query($connect, $next_query));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lesson['title']); ?> | <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .lesson-header {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #dee2e6;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .lesson-content {
            line-height: 1.6;
            font-size: 1.1rem;
        }
        .lesson-content img {
            max-width: 100%;
            height: auto;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <?php include('admin/includes/header.php'); ?>

    <div class="lesson-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="courses.php">Courses</a></li>
                    <li class="breadcrumb-item"><a href="course.php?id=<?php echo $lesson['course_id']; ?>">
                        <?php echo htmlspecialchars($lesson['course_title']); ?>
                    </a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo htmlspecialchars($lesson['title']); ?>
                    </li>
                </ol>
            </nav>
            <h1><?php echo htmlspecialchars($lesson['title']); ?></h1>
            <?php if($lesson['duration']): ?>
                <p class="text-muted">
                    <i class="fas fa-clock me-1"></i> <?php echo $lesson['duration']; ?> minutes
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <?php if($lesson['video_url']): ?>
                    <div class="video-container">
                        <iframe src="<?php echo htmlspecialchars($lesson['video_url']); ?>" 
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-body lesson-content">
                        <?php echo $lesson['content'] ? $lesson['content'] : 
                            '<p class="text-muted">No content available for this lesson.</p>'; ?>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <?php if($prev_lesson): ?>
                        <a href="lesson.php?id=<?php echo $prev_lesson['id']; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Previous Lesson
                        </a>
                    <?php else: ?>
                        <span></span>
                    <?php endif; ?>
                    
                    <?php if($next_lesson): ?>
                        <a href="lesson.php?id=<?php echo $next_lesson['id']; ?>" class="btn btn-primary">
                            Next Lesson <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    <?php else: ?>
                        <a href="course.php?id=<?php echo $lesson['course_id']; ?>" class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i> Return to Course
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">About This Course</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Course:</strong> 
                            <a href="course.php?id=<?php echo $lesson['course_id']; ?>">
                                <?php echo htmlspecialchars($lesson['course_title']); ?>
                            </a>
                        </p>
                        
                        <?php if($lesson['instructor_name']): ?>
                            <div class="d-flex align-items-center mb-3">
                                <?php if($lesson['instructor_photo']): ?>
                                    <img src="<?php echo htmlspecialchars($lesson['instructor_photo']); ?>" 
                                         class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <p class="mb-0"><strong>Instructor</strong></p>
                                    <p class="mb-0"><?php echo htmlspecialchars($lesson['instructor_name']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <a href="course.php?id=<?php echo $lesson['course_id']; ?>" class="btn btn-outline-primary w-100">
                            View All Lessons
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <?php include('admin/includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>