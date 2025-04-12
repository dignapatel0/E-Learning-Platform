<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $instructor_id = trim($_POST['instructor_id'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $image = trim($_POST['image'] ?? '');

  // Basic validation
  if (empty($title)) {
    $errors[] = 'Title is required.';
  }
  if (empty($instructor_id)) {
    $errors[] = 'Instructor must be selected.';
  }

  // If no errors, insert into database
  if (empty($errors)) {
    $stmt = mysqli_prepare($connect, '
      INSERT INTO courses (title, description, instructor_id, category, image) 
      VALUES (?, ?, ?, ?, ?)
    ');

    mysqli_stmt_bind_param($stmt, 'ssiss', $title, $description, $instructor_id, $category, $image);
    mysqli_stmt_execute($stmt);

    set_message('Course has been added');
    header('Location: courses.php');
    exit;
  }
}

include('includes/header.php');

// Get instructors for dropdown
$instructors_query = 'SELECT id, name FROM instructors ORDER BY name';
$instructors_result = mysqli_query($connect, $instructors_query);
?>

<h2>Add Course</h2>

<?php if (!empty($errors)): ?>
  <div style="color: red;">
    <ul>
      <?php foreach ($errors as $error): ?>
        <li><?php echo htmlentities($error); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post">
  <label for="title">Title:</label><br>
  <input type="text" name="title" id="title" required value="<?php echo htmlentities($_POST['title'] ?? ''); ?>">
  <br><br>

  <label for="description">Description:</label><br>
  <textarea name="description" id="description" rows="5"><?php echo htmlentities($_POST['description'] ?? ''); ?></textarea>
  <script>
    ClassicEditor
      .create(document.querySelector('#description'))
      .catch(error => {
          console.error(error);
      });
  </script>
  <br><br>

  <label for="instructor_id">Instructor:</label><br>
  <select name="instructor_id" id="instructor_id" required>
    <option value="">-- Select Instructor --</option>
    <?php while ($instructor = mysqli_fetch_assoc($instructors_result)): ?>
      <option value="<?php echo $instructor['id']; ?>"
        <?php if (!empty($_POST['instructor_id']) && $_POST['instructor_id'] == $instructor['id']) echo 'selected'; ?>>
        <?php echo htmlentities($instructor['name']); ?>
      </option>
    <?php endwhile; ?>
  </select>
  <br><br>

  <label for="category">Category:</label><br>
  <input type="text" name="category" id="category" value="<?php echo htmlentities($_POST['category'] ?? ''); ?>">
  <br><br>

  <input type="submit" value="Add Course">
</form>

<p><a href="courses.php"><i class="fas fa-arrow-circle-left"></i> Return to Course List</a></p>

<?php include('includes/footer.php'); ?>
