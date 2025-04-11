<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_POST['title']))
{
  if($_POST['title'])
  {
    $query = 'INSERT INTO courses (
        title,
        description,
        instructor_id,
        category,
        image
      ) VALUES (
         "'.mysqli_real_escape_string($connect, $_POST['title']).'",
         "'.mysqli_real_escape_string($connect, $_POST['description']).'",
         "'.mysqli_real_escape_string($connect, $_POST['instructor_id']).'",
         "'.mysqli_real_escape_string($connect, $_POST['category']).'",
         "'.mysqli_real_escape_string($connect, $_POST['image']).'"
      )';
    mysqli_query($connect, $query);
    
    set_message('Course has been added');
  }
  
  header('Location: courses.php');
  die();
}

include('includes/header.php');

// Get instructors for dropdown
$instructors_query = 'SELECT id, name FROM instructors ORDER BY name';
$instructors_result = mysqli_query($connect, $instructors_query);
?>

<h2>Add Course</h2>

<form method="post">
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" required>
    
  <br>
  
  <label for="description">Description:</label>
  <textarea name="description" id="description" rows="5"></textarea>
      
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
      <option value="<?php echo $instructor['id']; ?>">
        <?php echo htmlentities($instructor['name']); ?>
      </option>
    <?php endwhile; ?>
  </select>
  
  <br>
  
  <label for="category">Category:</label>
  <input type="text" name="category" id="category">
  
  <br>
  
  <input type="submit" value="Add Course">
  
</form>

<p><a href="courses.php"><i class="fas fa-arrow-circle-left"></i> Return to Course List</a></p>

<?php
include('includes/footer.php');
?>