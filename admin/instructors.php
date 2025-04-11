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

<h2>Manage Instructors</h2>

<table>
  <tr>
    <th></th>
    <th align="center">ID</th>
    <th align="left">Name</th>
    <th align="left">Email</th>
    <th align="center">Courses</th>
    <th></th>
    <th></th>
  </tr>
  <?php while($record = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td align="center">
        <?php if($record['photo']): ?>
          <img src="<?php echo $record['photo']; ?>" width="50" height="50" style="border-radius: 50%;">
        <?php endif; ?>
      </td>
      <td align="center"><?php echo $record['id']; ?></td>
      <td align="left">
        <?php echo htmlentities($record['name']); ?>
        <?php if($record['bio']): ?>
          <small><?php echo substr($record['bio'], 0, 50); ?>...</small>
        <?php endif; ?>
      </td>
      <td align="left"><?php echo $record['email']; ?></td>
      <td align="center">
        <?php 
          $count_query = 'SELECT COUNT(*) as count FROM courses WHERE instructor_id = '.$record['id'];
          $count_result = mysqli_query($connect, $count_query);
          $count = mysqli_fetch_assoc($count_result);
          echo $count['count'];
        ?>
      </td>
      <td align="center"><a href="instructors_photo.php?id=<?php echo $record['id']; ?>">Photo</i></a></td>
      <td align="center"><a href="instructors_edit.php?id=<?php echo $record['id']; ?>">Edit</a></td>
      <td align="center">
        <a href="instructors.php?delete=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this instructor?');">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="instructors_add.php"><i class="fas fa-plus-square"></i> Add Instructor</a></p>

<?php
include('includes/footer.php');
?>