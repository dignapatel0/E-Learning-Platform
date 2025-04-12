<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_GET['delete'])) {
    // Validate ID is numeric
    if(!is_numeric($_GET['delete'])) {
        set_message('Invalid lesson ID', 'error');
        header('Location: lessons.php');
        die();
    }

    $query = 'DELETE FROM lessons WHERE id = ? LIMIT 1';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $_GET['delete']);
    mysqli_stmt_execute($stmt);
    
    set_message('Lesson has been deleted');
    
    $redirect = 'lessons.php';
    if(isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
        $redirect .= '?course_id='.(int)$_GET['course_id'];
    }
    header('Location: '.$redirect);
    die();
}

include('includes/header.php');

// Get course title if filtering by course
$course_title = '';
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if($course_id > 0) {
    $course_query = 'SELECT title FROM courses WHERE id = ?';
    $stmt = mysqli_prepare($connect, $course_query);
    mysqli_stmt_bind_param($stmt, 'i', $course_id);
    mysqli_stmt_execute($stmt);
    $course_result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($course_result) > 0) {
        $course_record = mysqli_fetch_assoc($course_result);
        $course_title = htmlspecialchars($course_record['title']);
    }
}

// Prepare query with parameterized statement
$query = 'SELECT lessons.*, courses.title as course_title
          FROM lessons
          JOIN courses ON lessons.course_id = courses.id
          '.($course_id > 0 ? 'WHERE lessons.course_id = ?' : '').'
          ORDER BY sort_order ASC';

$stmt = mysqli_prepare($connect, $query);
if($course_id > 0) {
    mysqli_stmt_bind_param($stmt, 'i', $course_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h2>Manage Lessons <?php echo $course_title ? 'for "'.$course_title.'"' : ''; ?></h2>

<?php if($course_id > 0): ?>
  <p><a href="courses.php"><i class="fas fa-arrow-left"></i> Back to Courses</a></p>
<?php endif; ?>

<table>
  <tr>
    <th align="center">ID</th>
    <th align="left">Title</th>
    <th align="center">Course</th>
    <th align="center">Duration</th>
    <th align="center">Order</th>
    <th></th>
    <th></th>
  </tr>
  <?php while($record = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td align="center"><?php echo (int)$record['id']; ?></td>
      <td align="left">
        <?php echo htmlspecialchars($record['title']); ?>
        <?php if(!empty($record['video_url'])): ?>
          <small><i class="fas fa-video"></i> Has video</small>
        <?php endif; ?>
      </td>
      <td align="center"><?php echo htmlspecialchars($record['course_title']); ?></td>
      <td align="center"><?php echo (int)$record['duration']; ?> min</td>
      <td align="center"><?php echo (int)$record['sort_order']; ?></td>
      <td align="center"><a href="lessons_edit.php?id=<?php echo (int)$record['id']; ?>">Edit</a></td>
      <td align="center">
        <a href="lessons.php?delete=<?php echo (int)$record['id']; ?><?php echo $course_id > 0 ? '&course_id='.$course_id : ''; ?>" 
           onclick="return confirm('Are you sure you want to delete this lesson?');">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p>
  <a href="lessons_add.php<?php echo $course_id > 0 ? '?course_id='.$course_id : ''; ?>">
    <i class="fas fa-plus-square"></i> Add Lesson
  </a>
</p>

<?php
include('includes/footer.php');
?>