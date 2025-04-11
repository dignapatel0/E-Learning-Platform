<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_POST['name']))
{
  if($_POST['name'])
  {
    $query = 'INSERT INTO instructors (
        name,
        bio,
        email,
        photo
      ) VALUES (
         "'.mysqli_real_escape_string($connect, $_POST['name']).'",
         "'.mysqli_real_escape_string($connect, $_POST['bio']).'",
         "'.mysqli_real_escape_string($connect, $_POST['email']).'",
         "'.mysqli_real_escape_string($connect, $_POST['photo']).'"
      )';
    mysqli_query($connect, $query);
    
    set_message('Instructor has been added');
  }
  
  header('Location: instructors.php');
  die();
}

include('includes/header.php');
?>

<h2>Add Instructor</h2>

<form method="post">
  
  <label for="name">Name:</label>
  <input type="text" name="name" id="name" required>
    
  <br>
  
  <label for="email">Email:</label>
  <input type="email" name="email" id="email">
  
  <br>
  
  <label for="bio">Bio:</label>
  <textarea name="bio" id="bio" rows="5"></textarea>
      
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
  
  <input type="submit" value="Add Instructor">
  
</form>

<p><a href="instructors.php"><i class="fas fa-arrow-circle-left"></i> Return to Instructor List</a></p>

<?php
include('includes/footer.php');
?>