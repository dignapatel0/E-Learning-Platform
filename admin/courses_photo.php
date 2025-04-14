<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

// Config
$upload_dir = 'images/';
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 2 * 1024 * 1024; // 2MB

// Check if course ID is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: courses.php');
    die();
}

$course_id = (int)$_GET['id'];

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        set_message('Invalid file type. Only JPG, PNG, or GIF allowed.', 'error');
        header("Location: courses_photo.php?id=$course_id");
        die();
    }

    if ($_FILES['image']['size'] > $max_size) {
        set_message('File too large. Max 2MB allowed.', 'error');
        header("Location: courses_photo.php?id=$course_id");
        die();
    }

    // Check if images directory exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Get current image
    $query = 'SELECT image FROM courses WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $old_image = mysqli_fetch_assoc($result)['image'];

    // Generate unique filename
    $filename = basename($_FILES['image']['name']);
    $destination = $upload_dir . $filename;

    // Move the uploaded file to the images directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $query = 'UPDATE courses SET image = ? WHERE id = ?';
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'si', $filename, $course_id);
        mysqli_stmt_execute($stmt);

        // Delete old image if it exists
        if ($old_image && file_exists($upload_dir . $old_image)) {
            unlink($upload_dir . $old_image);
        }

        set_message('Course image updated successfully');
    } else {
        set_message('Error uploading image.', 'error');
    }

    header("Location: courses_photo.php?id=$course_id");
    die();
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $query = 'SELECT image FROM courses WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $image = mysqli_fetch_assoc($result)['image'];

    // Delete image from the server if it exists
    if ($image && file_exists($upload_dir . $image)) {
        unlink($upload_dir . $image);
    }

    // Remove image reference from database
    $query = 'UPDATE courses SET image = NULL WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $course_id);
    mysqli_stmt_execute($stmt);

    set_message('Course image deleted');
    header("Location: courses_photo.php?id=$course_id");
    die();
}

// Get course data
$query = 'SELECT * FROM courses WHERE id = ?';
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!mysqli_num_rows($result)) {
    header('Location: courses.php');
    die();
}

$record = mysqli_fetch_assoc($result);

include('includes/header.php');
?>

<div class="container">
    <h2>Edit Course Image</h2>
    <p>Recommended size: 800x450 pixels (16:9 ratio)</p>

    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($record['image']) && file_exists('images/' . $record['image'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Current Image</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="images/<?= htmlspecialchars($record['image']) ?>" class="img-fluid" style="max-width: 100%; height: auto;">
                        <div class="mt-3">
                            <a href="courses_photo.php?id=<?= $course_id ?>&delete" class="btn btn-danger" onclick="return confirm('Delete this image?')">
                                <i class="fas fa-trash-alt"></i> Delete Image
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No image uploaded yet.</div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Upload New Image</h5>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="image">Select Image:</label>
                            <input type="file" class="form-control-file" name="image" id="image" required>
                            <small class="form-text text-muted">Accepted: JPG, PNG, GIF. Max 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="courses.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Return to Course List
        </a>
    </div>
</div>

<?php include('includes/footer.php'); ?>
