<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

// Configuration
$upload_dir = 'images/';
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 2 * 1024 * 1024; // 2MB

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: instructors.php');
    die();
}

$instructor_id = (int)$_GET['id'];

// Handle photo upload
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    if (!in_array($_FILES['photo']['type'], $allowed_types)) {
        set_message('Invalid file type. Only JPG, PNG, or GIF allowed.', 'error');
        header("Location: instructors_photo.php?id=$instructor_id");
        die();
    }

    if ($_FILES['photo']['size'] > $max_size) {
        set_message('File too large. Max 2MB allowed.', 'error');
        header("Location: instructors_photo.php?id=$instructor_id");
        die();
    }

    // Create upload directory if not exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Get current photo from DB to delete later
    $query = 'SELECT photo FROM instructors WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $old_photo = mysqli_fetch_assoc($result)['photo'];

    // Use original filename
    $filename = basename($_FILES['photo']['name']);
    $destination = $upload_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        // Save only filename in DB
        $query = 'UPDATE instructors SET photo = ? WHERE id = ?';
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'si', $filename, $instructor_id);
        mysqli_stmt_execute($stmt);

        // Delete old photo if it exists
        if ($old_photo && file_exists($upload_dir . $old_photo)) {
            unlink($upload_dir . $old_photo);
        }

        set_message('Instructor photo updated successfully');
    } else {
        set_message('Error uploading photo.', 'error');
    }

    header("Location: instructors_photo.php?id=$instructor_id");
    die();
}

// Handle photo deletion
if (isset($_GET['delete'])) {
    $query = 'SELECT photo FROM instructors WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $photo = mysqli_fetch_assoc($result)['photo'];

    if ($photo && file_exists($upload_dir . $photo)) {
        unlink($upload_dir . $photo);
    }

    // Clear DB
    $query = 'UPDATE instructors SET photo = NULL WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
    mysqli_stmt_execute($stmt);

    set_message('Instructor photo deleted');
    header("Location: instructors_photo.php?id=$instructor_id");
    die();
}

// Fetch instructor record
$query = 'SELECT * FROM instructors WHERE id = ?';
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!mysqli_num_rows($result)) {
    header('Location: instructors.php');
    die();
}

$record = mysqli_fetch_assoc($result);
include('includes/header.php');
?>

<div class="container">
    <h2>Edit Instructor Photo</h2>

    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($record['photo']) && file_exists("../images/" . $record['photo'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Current Photo</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="../images/<?= htmlspecialchars($record['photo']) ?>" 
                             class="rounded-circle"
                             style="width: 200px; height: 200px; object-fit: cover;">
                        <div class="mt-3">
                            <a href="instructors_photo.php?id=<?= $instructor_id ?>&delete" 
                               class="btn btn-danger"
                               onclick="return confirm('Delete this photo?')">
                                <i class="fas fa-trash-alt"></i> Delete Photo
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No photo uploaded yet</div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Upload Photo</h5>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="photo">Select Photo:</label>
                            <input type="file" class="form-control-file" name="photo" id="photo" required>
                            <small class="text-muted">Max 2MB (JPG, PNG, GIF). Square photos work best.</small>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-upload"></i> Upload Photo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="instructors.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Return to Instructor List
        </a>
    </div>
</div>

<?php include('includes/footer.php'); ?>
