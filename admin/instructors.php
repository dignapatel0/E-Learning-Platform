<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_GET['delete']))
{
  $query = 'DELETE FROM instructors
    WHERE id = '.$_GET['delete'].' 
    LIMIT 1';
  mysqli_query($connect, $query);
    
  set_message('Instructor has been deleted');
  
  header('Location: instructors.php');
  die();
}

include('includes/header.php');

$query = 'SELECT *
  FROM instructors
  ORDER BY name ASC';
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
          <th width="150">Courses</th>
          <th width="200">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($record = mysqli_fetch_assoc($result)): ?>
          <tr class="instructor-row">
            <td class="text-center">
              <?php if($record['photo']): ?>
                <img src="<?= htmlspecialchars($record['photo']); ?>" 
                     width="50" height="50" class="rounded-circle" 
                     alt="Instructor Photo">
              <?php endif; ?>
            </td>
            <td class="text-center"><?= (int)$record['id']; ?></td>
            <td>
              <strong><?= htmlentities($record['name']); ?></strong>
              <?php if($record['bio']): ?>
                <div class="text-muted small"><?= htmlentities(substr($record['bio'], 0, 50)); ?>...</div>
              <?php endif; ?>
            </td>
            <td><?= htmlentities($record['email']); ?></td>
            <td class="text-center">
              <?php 
                $count_query = 'SELECT COUNT(*) as count FROM courses WHERE instructor_id = '.$record['id'];
                $count_result = mysqli_query($connect, $count_query);
                $count = mysqli_fetch_assoc($count_result);
                echo $count['count'];
              ?>
            </td>
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
