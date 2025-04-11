<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(!isset($_GET['id']))
{
  header('Location: instructors.php');
  die();
}

if(isset($_POST['name']))
{
  if($_POST['name'])
  {
    $query = 'UPDATE instructors SET
      name = "'.mysqli_real_escape_string($connect, $_POST['name']).'",
      bio = "'.mysqli_real_escape_string($connect, $_POST['bio']).'",
      email = "'.mysqli_real_escape_string($connect, $_POST['email']).'",
      photo = "'.mysqli_real_escape_string($connect, $_POST['photo']).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query($connect, $query);
    
    set_message('Instructor has been updated');
  }

  header('Location: instructors.php');
  die();
}

if(isset($_GET['id']))
{
  $query = 'SELECT *
    FROM instructors
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query($connect, $query);
  
  if(!mysqli_num_rows($result))
  {
    header('Location: instructors.php');
    die();
  }
  
  $record = mysqli_fetch_assoc($result);
}

include('includes/header.php');
?>

<h2>Edit Instructor</h2>

<form method="post">
  
  <label for="name">Name:</label>
  <input type="text" name="name" id="name" value="<?php echo htmlentities($record['name']); ?>" required>
    
  <br>
  
  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?php echo htmlentities($record['email']); ?>">
  
  <br>
  
  <label for="bio">Bio:</label>
  <textarea name="bio" id="bio" rows="5"><?php echo htmlentities($record['bio']); ?></textarea>
      
  <script>
  ClassicEditor
    .create(document.querySelector('#bio'))
    .then(editor => {
        console.log(editor);
    })
    .catch(error => {
        console.error(error);
    });
  </script>
  
  <br>
  
  <input type="submit" value="Update Instructor">
  
</form>

<p><a href="instructors.php"><i class="fas fa-arrow-circle-left"></i> Return to Instructor List</a></p>

<?php
include('includes/footer.php');
?>