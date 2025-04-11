<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(!isset($_GET['id']))
{
  header('Location: lessons.php');
  die();
}

if(isset($_POST['title']))
{
  if($_POST['title'] && $_POST['course_id'])
  {
    $query = 'UPDATE lessons SET
      course_id = "'.mysqli_real_escape_string($connect, $_POST['course_id']).'",
      title = "'.mysqli_real_escape_string($connect, $_POST['title']).'",
      video_url = "'.mysqli_real_escape_string($connect, $_POST['video_url']).'",
      content = "'.mysqli_real_escape_string($connect, $_POST['content']).'",
      duration = "'.mysqli_real_escape_string($connect, $_POST['duration']).'",
      sort_order = "'.mysqli_real_escape_string($connect, $_POST['sort_order']).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query($connect, $query);
    
    set_message('Lesson has been updated');
  }

  // Redirect back to lessons.php, preserving course_id if it was passed
  $redirect = 'lessons.php';
  if(isset($_GET['course_id'])) {
    $redirect .= '?course_id='.$_GET['course_id'];
  }
  header('Location: '.$redirect);
  die();
}

if(isset($_GET['id']))
{
  $query = 'SELECT *
    FROM lessons
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query($connect, $query);
  
  if(!mysqli_num_rows($result))
  {
    header('Location: lessons.php');
    die();
  }
  
  $record = mysqli_fetch_assoc($result);
}

include('includes/header.php');

// Get courses for dropdown
$courses_query = 'SELECT id, title FROM courses ORDER BY title';
$courses_result = mysqli_query($connect, $courses_query);
?>

<h2>Edit Lesson</h2>

<form method="post">
  
  <label for="course_id">Course:</label>
  <select name="course_id" id="course_id" required>
    <option value="">-- Select Course --</option>
    <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
      <option value="<?php echo $course['id']; ?>"
        <?php if($course['id'] == $record['course_id']) echo 'selected'; ?>>
        <?php echo htmlentities($course['title']); ?>
      </option>
    <?php endwhile; ?>
  </select>
  
  <br>
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" value="<?php echo htmlentities($record['title']); ?>" required>
    
  <br>
  
  <label for="video_url">Video URL:</label>
  <input type="text" name="video_url" id="video_url" value="<?php echo htmlentities($record['video_url']); ?>">
  
  <br>
  
  <label for="content">Content:</label>
  <textarea name="content" id="content" rows="10"><?php echo htmlentities($record['content']); ?></textarea>
      
  <script>
  ClassicEditor
    .create(document.querySelector('#content'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
  </script>
  
  <br>
  
  <label for="duration">Duration (minutes):</label>
  <input type="number" name="duration" id="duration" min="1" value="<?php echo htmlentities($record['duration']); ?>">
  
  <br>
  
  <label for="sort_order">Sort Order:</label>
  <input type="number" name="sort_order" id="sort_order" min="0" value="<?php echo htmlentities($record['sort_order']); ?>">
  
  <br>
  
  <input type="submit" value="Update Lesson">
  
</form>

<p><a href="lessons.php<?php echo isset($_GET['course_id']) ? '?course_id='.$_GET['course_id'] : ''; ?>">
  <i class="fas fa-arrow-circle-left"></i> Return to Lesson List
</a></p>

<?php
include('includes/footer.php');
?>