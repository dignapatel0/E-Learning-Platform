<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

// Validate instructor ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: instructors.php');
    exit();
}

$instructor_id = (int)$_GET['id'];

// Get instructor details
$query = "SELECT * FROM instructors WHERE id = $instructor_id";
$result = mysqli_query($connect, $query);
$instructor = mysqli_fetch_assoc($result);

if (!$instructor) {
    header('Location: instructors.php');
    exit();
}

// Get courses taught by this instructor
$courses_query = "SELECT c.* FROM courses c
                 WHERE c.instructor_id = $instructor_id
                 ORDER BY c.created_at DESC";
$courses_result = mysqli_query($connect, $courses_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($instructor['name']); ?> | <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .instructor-header {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .instructor-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background: #f1f1f1;
            margin-right: 10px;
            color: #333;
            transition: all 0.3s;
        }
        .social-links a:hover {
            background: #0d6efd;
            color: white;
        }
        .course-card {
            transition: transform 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php include('admin/includes/header.php'); ?>

    <div class="instructor-header text-center">
        <div class="container">
            <?php if($instructor['photo']): ?>
                <img src="<?php echo htmlspecialchars($instructor['photo']); ?>" 
                     class="instructor-img mb-3" alt="<?php echo htmlspecialchars($instructor['name']); ?>">
            <?php else: ?>
                <div class="instructor-img mx-auto mb-3 bg-secondary text-white d-flex align-items-center justify-content-center">
                    <i class="fas fa-user fa-3x"></i>
                </div>
            <?php endif; ?>
            
            <h1><?php echo htmlspecialchars($instructor['name']); ?></h1>
            
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">About</h5>
                    </div>
                    <div class="card-body">
                        
                        <?php if($instructor['bio']): ?>
                            <?php echo display_html_content($instructor['bio']); ?>
                            <?php else: ?>
                            <p class="text-muted">No biography available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Contact</h5>
                    </div>
                    <div class="card-body">
                        <?php if($instructor['email']): ?>
                            <p>
                                <i class="fas fa-envelope me-2"></i> 
                                <a href="mailto:<?php echo htmlspecialchars($instructor['email']); ?>">
                                    <?php echo htmlspecialchars($instructor['email']); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Courses by <?php echo htmlspecialchars($instructor['name']); ?></h5>
                            <span class="badge bg-primary">
                                <?php echo mysqli_num_rows($courses_result); ?> courses
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($courses_result) > 0): ?>
                            <div class="row g-4">
                                <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
                                    <div class="col-md-6">
                                        <div class="card course-card h-100">
                                            <?php if($course['image']): ?>
                                                <img src="<?php echo htmlspecialchars($course['image']); ?>" 
                                                     class="card-img-top" style="height: 150px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="height: 150px;">
                                                    <i class="fas fa-book fa-3x"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                                <?php if($course['category']): ?>
                                                    <span class="badge bg-info mb-2">
                                                        <?php echo htmlspecialchars($course['category']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <p class="card-text">
                                                    <?php echo display_html_content($course['description'] ?? '', 0, 100); ?>...
                                                </p>
                                            </div>
                                            <div class="card-footer bg-transparent">
                                                <a href="course.php?id=<?php echo $course['id']; ?>" 
                                                   class="btn btn-sm btn-primary w-100">
                                                    View Course
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">This instructor hasn't published any courses yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('admin/includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>