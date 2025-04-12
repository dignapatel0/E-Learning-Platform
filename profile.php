<?php
require_once 'admin/includes/database.php';
require_once 'admin/includes/config.php';
require_once 'admin/includes/functions.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first'] ?? '');
    $last = trim($_POST['last'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    $errors = [];
    
    if (empty($first)) {
        $errors[] = 'First name is required';
    }
    
    if (empty($last)) {
        $errors[] = 'Last name is required';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }
    
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match';
        }
    }

    // Check if email exists (excluding current user)
    $email_check = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = mysqli_prepare($connect, $email_check);
    mysqli_stmt_bind_param($stmt, 'si', $email, $user_id);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
        $errors[] = 'Email already in use by another account';
    }

    if (empty($errors)) {
        // Update profile
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET first = ?, last = ?, email = ?, password = ? WHERE id = ?";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, 'ssssi', $first, $last, $email, $hashed_password, $user_id);
        } else {
            $query = "UPDATE users SET first = ?, last = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, 'sssi', $first, $last, $email, $user_id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = 'Profile updated successfully';
            
            // Update session variables
            $_SESSION['first'] = $first;
            $_SESSION['email'] = $email;
            
            header('Location: profile.php');
            exit();
        } else {
            $_SESSION['error'] = 'Error updating profile';
        }
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

// Get user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Get enrolled courses
$courses_query = "SELECT courses.id, courses.title, courses.image, 
                 enrollments.enrolled_at, enrollments.completed
                 FROM enrollments
                 JOIN courses ON enrollments.course_id = courses.id
                 WHERE enrollments.user_id = ?
                 ORDER BY enrollments.enrolled_at DESC";
$stmt = mysqli_prepare($connect, $courses_query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$enrolled_courses = mysqli_stmt_get_result($stmt);

include('admin/includes/header.php');
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="profile-picture mb-3">
                        <div class="initials-circle">
                            <?= strtoupper(substr($user['first'], 0, 1) . substr($user['last'], 0, 1)) ?>
                        </div>
                    </div>
                    <h4><?= htmlspecialchars($user['first'] . ' ' . $user['last']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <p class="text-muted">
                        Member since <?= date('M Y', strtotime($user['dateAdded'])) ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#profile" data-toggle="tab">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#courses" data-toggle="tab">My Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#password" data-toggle="tab">Password</a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="tab-pane active" id="profile">
                            <h5>Edit Profile</h5>
                            <?php display_messages(); ?>
                            
                            <form method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="first">First Name</label>
                                        <input type="text" class="form-control" id="first" name="first" 
                                               value="<?= htmlspecialchars($user['first']) ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="last">Last Name</label>
                                        <input type="text" class="form-control" id="last" name="last" 
                                               value="<?= htmlspecialchars($user['last']) ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                        
                        <!-- Courses Tab -->
                        <div class="tab-pane" id="courses">
                            <h5>My Courses</h5>
                            
                            <?php if (mysqli_num_rows($enrolled_courses) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Enrolled On</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($course = mysqli_fetch_assoc($enrolled_courses)): ?>
                                                <tr>
                                                    <td>
                                                        <a href="course.php?id=<?= $course['id'] ?>">
                                                            <?= htmlspecialchars($course['title']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?= date('M j, Y', strtotime($course['enrolled_at'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($course['completed'] == 'Yes'): ?>
                                                            <span class="badge badge-success">Completed</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-primary">In Progress</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="course.php?id=<?= $course['id'] ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            Continue
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    You haven't enrolled in any courses yet. 
                                    <a href="courses.php" class="alert-link">Browse courses</a> to get started!
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Password Tab -->
                        <div class="tab-pane" id="password">
                            <h5>Change Password</h5>
                            
                            <form method="post">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control" id="password" 
                                           name="password" minlength="8" required>
                                    <small class="form-text text-muted">
                                        At least 8 characters
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-picture {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.initials-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: bold;
}

.nav-tabs .nav-link {
    color: #495057;
}

.nav-tabs .nav-link.active {
    font-weight: bold;
}
</style>

<script>
// Enable tab functionality
$(document).ready(function(){
    $('.nav-tabs a').click(function(e){
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Handle hash in URL for direct tab access
    const hash = window.location.hash;
    if (hash) {
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
    }
});
</script>

<?php include('admin/includes/footer.php'); ?>