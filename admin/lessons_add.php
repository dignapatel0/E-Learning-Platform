<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_POST['title']))
{
  if($_POST['title'] && $_POST['course_id'])
  {
    $query = 'INSERT INTO lessons (
        course_id,
        title,
        video_url,
        content,
        duration,
        sort_order
      ) VALUES (
         "'.mysqli_real_escape_string($connect, $_POST['course_id']).'",
         "'.mysqli_real_escape_string($connect, $_POST['title']).'",
         "'.mysqli_real_escape_string($connect, $_POST['video_url']).'",
         "'.mysqli_real_escape_string($connect, $_POST['content']).'",
         "'.mysqli_real_escape_string($connect, $_POST['duration']).'",
         "'.mysqli_real_escape_string($connect, $_POST['sort_order']).'"
      )';
    mysqli_query($connect, $query);
    
    set_message('Lesson has been added');
  }
  
  // Redirect back to lessons.php, preserving course_id if it was passed
  $redirect = 'lessons.php';
  if(isset($_GET['course_id'])) {
    $redirect .= '?course_id='.$_GET['course_id'];
  }
  header('Location: '.$redirect);
  die();
}

include('includes/header.php');

// Get courses for dropdown
$courses_query = 'SELECT id, title FROM courses ORDER BY title';
$courses_result = mysqli_query($connect, $courses_query);
?>

<h2>Add Lesson</h2>

<form method="post">
  
  <label for="course_id">Course:</label>
  <select name="course_id" id="course_id" required>
    <option value="">-- Select Course --</option>
    <?php while($course = mysqli_fetch_assoc($courses_result)): ?>
      <option value="<?php echo $course['id']; ?>" 
        <?php if(isset($_GET['course_id']) && $_GET['course_id'] == $course['id']) echo 'selected'; ?>>
        <?php echo htmlentities($course['title']); ?>
      </option>
    <?php endwhile; ?>
  </select>
  
  <br>
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" required>
    
  <br>
  
  <label for="video_url">Video URL:</label>
  <input type="text" name="video_url" id="video_url">
  
  <br>
  
  <label for="content">Content:</label>
  <textarea name="content" id="content" rows="10"></textarea>
      
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
  <input type="number" name="duration" id="duration" min="1">
  
  <br>
  
  <label for="sort_order">Sort Order:</label>
  <input type="number" name="sort_order" id="sort_order" min="0" value="0">
  
  <br>
  
  <input type="submit" value="Add Lesson">
  
</form>

<p><a href="lessons.php<?php echo isset($_GET['course_id']) ? '?course_id='.$_GET['course_id'] : ''; ?>">
  <i class="fas fa-arrow-circle-left"></i> Return to Lesson List
</a></p>

<?php
include('includes/footer.php');
?>