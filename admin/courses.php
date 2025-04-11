<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_GET['delete']))
{
  $query = 'DELETE FROM courses
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query($connect, $query);
    
  set_message('Course has been deleted');
  
  header('Location: courses.php');
  die();
}

include('includes/header.php');

$query = 'SELECT courses.*, instructors.name as instructor_name
  FROM courses
  LEFT JOIN instructors ON courses.instructor_id = instructors.id
  ORDER BY title ASC';
$result = mysqli_query($connect, $query);
?>

<h2>Manage Courses</h2>

<table>
  <tr>
    <th></th>
    <th align="center">ID</th>
    <th align="left">Title</th>
    <th align="center">Instructor</th>
    <th align="center">Category</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while($record = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td align="center">
        <?php if($record['thumbnail']): ?>
          <img src="<?php echo $record['thumbnail']; ?>" width="100">
        <?php endif; ?>
      </td>
      <td align="center"><?php echo $record['id']; ?></td>
      <td align="left">
        <?php echo htmlentities($record['title']); ?>
        <small><?php echo substr($record['description'], 0, 100); ?>...</small>
      </td>
      <td align="center"><?php echo $record['instructor_name'] ?? 'N/A'; ?></td>
      <td align="center"><?php echo $record['category']; ?></td>
      <td align="center"><a href="lessons.php?course_id=<?php echo $record['id']; ?>">Lessons</a></td>        
      <td align="center"><a href="courses_photo.php?id=<?php echo $record['id']; ?>">Photo</i></a></td>
      <td align="center"><a href="courses_edit.php?id=<?php echo $record['id']; ?>">Edit</a></td>
      <td align="center">
        <a href="courses.php?delete=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="courses_add.php"><i class="fas fa-plus-square"></i> Add Course</a></p>

<?php
include('includes/footer.php');
?>