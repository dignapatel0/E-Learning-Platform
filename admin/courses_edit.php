<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(!isset($_GET['id']))
{
  header('Location: courses.php');
  die();
}

if(isset($_POST['title']))
{
  if($_POST['title'])
  {
    $query = 'UPDATE courses SET
      title = "'.mysqli_real_escape_string($connect, $_POST['title']).'",
      description = "'.mysqli_real_escape_string($connect, $_POST['description']).'",
      instructor_id = "'.mysqli_real_escape_string($connect, $_POST['instructor_id']).'",
      category = "'.mysqli_real_escape_string($connect, $_POST['category']).'",
      image = "'.mysqli_real_escape_string($connect, $_POST['image']).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query($connect, $query);
    
    set_message('Course has been updated');
  }

  header('Location: courses.php');
  die();
}

if(isset($_GET['id']))
{
  $query = 'SELECT *
    FROM courses
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query($connect, $query);
  
  if(!mysqli_num_rows($result))
  {
    header('Location: courses.php');
    die();
  }
  
  $record = mysqli_fetch_assoc($result);
}

include('includes/header.php');

// Get instructors for dropdown
$instructors_query = 'SELECT id, name FROM instructors ORDER BY name';
$instructors_result = mysqli_query($connect, $instructors_query);
?>

<h2>Edit Course</h2>

<form method="post">
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" value="<?php echo htmlentities($record['title']); ?>" required>
    
  <br>
  
  <label for="description">Description:</label>
  <textarea name="description" id="description" rows="5"><?php echo htmlentities($record['description']); ?></textarea>
      
  <script>
  ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
  </script>
  
  <br>
  
  <label for="instructor_id">Instructor:</label>
  <select name="instructor_id" id="instructor_id">
    <option value="">-- Select Instructor --</option>
    <?php while($instructor = mysqli_fetch_assoc($instructors_result)): ?>
      <option value="<?php echo $instructor['id']; ?>"
        <?php if($instructor['id'] == $record['instructor_id']) echo 'selected'; ?>>
        <?php echo htmlentities($instructor['name']); ?>
      </option>
    <?php endwhile; ?>
  </select>
  
  <br>
  
  <label for="category">Category:</label>
  <input type="text" name="category" id="category" value="<?php echo htmlentities($record['category']); ?>">
  
  <br>
  
  <input type="submit" value="Update Course">
  
</form>

<p><a href="courses.php"><i class="fas fa-arrow-circle-left"></i> Return to Course List</a></p>

<?php
include('includes/footer.php');
?>