<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

// DELETE LESSON
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $delete_id = (int) $_GET['delete'];

  $query = "DELETE FROM lessons WHERE id = $delete_id LIMIT 1";
  mysqli_query($connect, $query) or die(mysqli_error($connect));

  set_message('Lesson has been deleted');

  header('Location: lessons.php' . (isset($_GET['course_id']) ? '?course_id=' . (int)$_GET['course_id'] : ''));
  die();
}

include('includes/header.php');

// Get course title if filtering by course
$course_title = '';
if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
  $course_id = (int) $_GET['course_id'];
  $course_query = "SELECT title FROM courses WHERE id = $course_id";
  $course_result = mysqli_query($connect, $course_query);
  $course_record = mysqli_fetch_assoc($course_result);
  if ($course_record) {
    $course_title = $course_record['title'];
  }
}

// Fetch all lessons (optionally filtered by course_id)
$lessons_query = "SELECT lessons.*, courses.title AS course_title
  FROM lessons
  JOIN courses ON lessons.course_id = courses.id " .
  (isset($_GET['course_id']) && is_numeric($_GET['course_id']) ? "WHERE lessons.course_id = $course_id " : '') . "
  ORDER BY sort_order ASC";

$result = mysqli_query($connect, $lessons_query) or die(mysqli_error($connect));
?>

<div class="container">
  <h2 class="my-4 text-center">Manage Lessons <?php echo $course_title ? 'for "' . htmlentities($course_title) . '"' : ''; ?></h2>

  <?php if (isset($_GET['course_id'])): ?>
    <p><a href="courses.php"><i class="fas fa-arrow-left"></i> Back to Courses</a></p>
  <?php endif; ?>

  <div class="mb-3">
    <a href="lessons_add.php<?php echo isset($_GET['course_id']) ? '?course_id=' . $course_id : ''; ?>" class="btn-add">
      <i class="fas fa-plus-square"></i> Add Lesson
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th width="80">ID</th>
          <th>Title</th>
          <th width="150">Course</th>
          <th width="120">Duration</th>
          <th width="100">Order</th>
          <th width="150">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($record = mysqli_fetch_assoc($result)): ?>
          <tr class="lesson-row">
            <td class="text-center"><?= (int)$record['id'] ?></td>
            <td>
              <strong><?= htmlentities($record['title']) ?></strong>
              <?php if ($record['video_url']): ?>
                <small><i class="fas fa-video"></i> Has video</small>
              <?php endif; ?>
            </td>
            <td class="text-center"><?= htmlentities($record['course_title']) ?></td>
            <td class="text-center"><?= $record['duration'] !== null ? $record['duration'] . ' min' : '-' ?></td>
            <td class="text-center"><?= $record['sort_order'] ?></td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                <a href="lessons_edit.php?id=<?= (int)$record['id'] ?>" class="btn btn-outline-info">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="lessons.php?delete=<?= (int)$record['id'] ?><?php echo isset($_GET['course_id']) ? '&course_id=' . $course_id : ''; ?>" 
                   class="btn btn-outline-danger" 
                   onclick="return confirm('Are you sure you want to delete this lesson?');">
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
