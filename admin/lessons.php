<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_GET['delete']))
{
  $query = 'DELETE FROM lessons
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query($connect, $query);
    
  set_message('Lesson has been deleted');
  
  header('Location: lessons.php'.(isset($_GET['course_id']) ? '?course_id='.$_GET['course_id'] : ''));
  die();
}

include('includes/header.php');

// Get course title if filtering by course
$course_title = '';
if(isset($_GET['course_id'])) {
  $course_query = 'SELECT title FROM courses WHERE id = '.$_GET['course_id'];
  $course_result = mysqli_query($connect, $course_query);
  $course_record = mysqli_fetch_assoc($course_result);
  $course_title = $course_record['title'];
}

$query = 'SELECT lessons.*, courses.title as course_title
  FROM lessons
  JOIN courses ON lessons.course_id = courses.id
  '.($_GET['course_id'] ? 'WHERE lessons.course_id = '.$_GET['course_id'] : '').'
  ORDER BY sort_order ASC';
$result = mysqli_query($connect, $query);
?>

<h2>Manage Lessons <?php echo $course_title ? 'for "'.$course_title.'"' : ''; ?></h2>

<?php if(isset($_GET['course_id'])): ?>
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
    <th></th>
  </tr>
  <?php while($record = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td align="center"><?php echo $record['id']; ?></td>
      <td align="left">
        <?php echo htmlentities($record['title']); ?>
        <?php if($record['video_url']): ?>
          <small><i class="fas fa-video"></i> Has video</small>
        <?php endif; ?>
      </td>
      <td align="center"><?php echo $record['course_title']; ?></td>
      <td align="center"><?php echo $record['duration']; ?> min</td>
      <td align="center"><?php echo $record['sort_order']; ?></td>
      <td align="center"><a href="lessons_edit.php?id=<?php echo $record['id']; ?>">Edit</a></td>
      <td align="center">
        <a href="lessons.php?delete=<?php echo $record['id']; ?><?php echo isset($_GET['course_id']) ? '&course_id='.$_GET['course_id'] : ''; ?>" onclick="return confirm('Are you sure you want to delete this lesson?');">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p>
  <a href="lessons_add.php<?php echo isset($_GET['course_id']) ? '?course_id='.$_GET['course_id'] : ''; ?>">
    <i class="fas fa-plus-square"></i> Add Lesson
  </a>
</p>

<?php
include('includes/footer.php');
?>