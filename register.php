<?php
require_once 'admin/includes/database.php';
require_once 'admin/includes/config.php';
require_once 'admin/includes/functions.php';

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit();
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first'] ?? '');
    $last = trim($_POST['last'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Validate
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
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // Check if email exists
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
        $errors[] = 'Email already registered';
    }
    
    if (empty($errors)) {
        // Create user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (first, last, email, password, active, dateAdded) 
                  VALUES (?, ?, ?, ?, 'Yes', NOW())";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $first, $last, $email, $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            $user_id = mysqli_insert_id($connect);
            
            // Auto-login
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['first'] = $first;
            
            // Send welcome email
            $subject = "Welcome to Our E-Learning Platform";
            $message = "Hi $first,\n\n";
            $message .= "Thank you for registering with us!\n\n";
            $message .= "Start learning today by browsing our courses.\n\n";
            $message .= "Best regards,\nThe E-Learning Team";
            
            send_email($email, $subject, $message);
            
            // Redirect to profile
            header('Location: profile.php');
            exit();
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}

include('admin/includes/header.php');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Create Your Account</h2>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= $error ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="first">First Name</label>
                                <input type="text" class="form-control" id="first" name="first" 
                                       value="<?= htmlspecialchars($_POST['first'] ?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last">Last Name</label>
                                <input type="text" class="form-control" id="last" name="last" 
                                       value="<?= htmlspecialchars($_POST['last'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   minlength="8" required>
                            <small class="form-text text-muted">At least 8 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Register</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center mb-0">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('admin/includes/footer.php'); ?>