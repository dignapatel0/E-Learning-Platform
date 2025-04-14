<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $instructor_id = (int)$_GET['delete'];

    // Get the instructor's current photo
    $query = 'SELECT photo FROM instructors WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $instructor = mysqli_fetch_assoc($result);

    // Delete the photo file if it exists
    if ($instructor['photo'] && file_exists('images/' . $instructor['photo'])) {
        unlink('images/' . $instructor['photo']);
    }

    // Delete the instructor record
    $query = 'DELETE FROM instructors WHERE id = ? LIMIT 1';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $instructor_id);
    mysqli_stmt_execute($stmt);

    set_message('Instructor has been deleted');
    header('Location: instructors.php');
    die();
}

include('includes/header.php');

$query = 'SELECT * FROM instructors ORDER BY name ASC';
$result = mysqli_query($connect, $query);
?>

<div class="container">
  <h2 class="my-4 text-center">Manage Instructors</h2>

  <div class="mb-3">
    <a href="instructors_add.php" class="btn-add">
      <i class="fas fa-plus-square"></i> Add Instructor
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th width="100">Photo</th>
          <th width="80">ID</th>
          <th>Name</th>
          <th>Email</th>
          <th width="200">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($record = mysqli_fetch_assoc($result)): ?>
          <tr class="instructor-row">
            <td class="text-center">
              <?php if (!empty($record['photo']) && file_exists('images/' . $record['photo'])): ?>
                <img src="images/<?= htmlspecialchars($record['photo']); ?>"
                     width="50" height="50"
                     class="rounded-circle"
                     alt="Instructor Photo">
              <?php else: ?>
                <div class="text-muted">
                  <i class="fas fa-user-circle fa-2x"></i>
                </div>
              <?php endif; ?>
            </td>
            <td class="text-center"><?= (int)$record['id']; ?></td>
            <td>
              <strong><?= htmlspecialchars($record['name']) ?></strong>
              <div class="text-muted small">
                <?= htmlspecialchars(mb_strimwidth(strip_tags($record['bio']), 0, 100, (strlen(strip_tags($record['bio'])) > 100 ? '...' : ''))) ?>
              </div>
            </td>
            <td><?= htmlentities($record['email']); ?></td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                <a href="instructors_photo.php?id=<?= (int)$record['id']; ?>" class="btn btn-outline-secondary">
                  <i class="fas fa-camera"></i> Photo
                </a>
                <a href="instructors_edit.php?id=<?= (int)$record['id']; ?>" class="btn btn-outline-info">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="instructors.php?delete=<?= (int)$record['id']; ?>"
                   class="btn btn-outline-danger"
                   onclick="return confirm('Are you sure you want to delete this instructor?');">
                  <i class="fas fa-trash-alt"></i> Delete
                </a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include('includes/footer.php'); ?>
