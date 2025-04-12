<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'course.php?id=' . $_GET['course_id'];
    header('Location: login.php');
    exit();
}

// Validate course ID
if (!isset($_GET['course_id']) || !is_numeric($_GET['course_id'])) {
    set_message('Invalid course selection', 'error');
    header('Location: courses.php');
    exit();
}

$course_id = (int)$_GET['course_id'];
$user_id = (int)$_SESSION['user_id'];

// Check if course exists
$course_check = mysqli_query($connect, "SELECT id FROM courses WHERE id = $course_id");
if (mysqli_num_rows($course_check) === 0) {
    set_message('Course not found', 'error');
    header('Location: courses.php');
    exit();
}

// Check if already enrolled
$enrollment_check = mysqli_query($connect, 
    "SELECT id FROM enrollments 
     WHERE user_id = $user_id AND course_id = $course_id"
);

if (mysqli_num_rows($enrollment_check) > 0) {
    set_message('You are already enrolled in this course', 'info');
    header("Location: course.php?id=$course_id");
    exit();
}

// Process enrollment
$enroll_query = "INSERT INTO enrollments (user_id, course_id, enrolled_at) 
                 VALUES ($user_id, $course_id, NOW())";

if (mysqli_query($connect, $enroll_query)) {
    set_message('Successfully enrolled in the course!', 'success');
} else {
    set_message('Enrollment failed: ' . mysqli_error($connect), 'error');
}

header("Location: course.php?id=$course_id");
exit();
?>