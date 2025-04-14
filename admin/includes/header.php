<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>E-Learning Platform</title>
  
  <link href="styles.css" type="text/css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script>
</head>
<body>
  <?php
  // Determine the current page to highlight the active link
  $current_page = basename($_SERVER['PHP_SELF']);
  ?>

  <div class="main-container">
    <!-- Vertical Header/Sidebar -->
    <?php if (isset($_SESSION['id'])): ?>
    <div class="vertical-header">
      <a class="navbar-brand fw-bold" href="dashboard.php">E-Learning Platform</a>
      
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'courses.php') ? 'active' : ''; ?>" href="courses.php">
            <i class="fas fa-book"></i> Courses
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'lessons.php') ? 'active' : ''; ?>" href="lessons.php">
            <i class="fas fa-list-ol"></i> Lessons
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'instructors.php') ? 'active' : ''; ?>" href="instructors.php">
            <i class="fas fa-chalkboard-teacher"></i> Instructors
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>" href="users.php">
            <i class="fas fa-users"></i> Users
          </a>
        </li>
      </ul>
      
      <div class="user-info mt-auto">
        <a href="logout.php" class="btn btn-sm btn-outline-light">
          <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
      </div>
    </div>
    <?php endif; ?>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <?php echo get_message(); ?>
      <!-- Main content will go here -->