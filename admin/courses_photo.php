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

if(isset($_FILES['image']))
{
  if(isset($_FILES['image']))
  {
    if($_FILES['image']['error'] == 0)
    {
      switch($_FILES['image']['type'])
      {
        case 'image/png': 
          $type = 'png'; 
          break;
        case 'image/jpg':
        case 'image/jpeg':
          $type = 'jpeg'; 
          break;
        case 'image/gif': 
          $type = 'gif'; 
          break;      
      }

      $query = 'UPDATE courses SET
        image = "data:image/'.$type.';base64,'.base64_encode(file_get_contents($_FILES['image']['tmp_name'])).'"
        WHERE id = '.$_GET['id'].'
        LIMIT 1';
      mysqli_query($connect, $query);
    }
  }
  
  set_message('Course image has been updated');
  header('Location: courses.php');
  die();
}

if(isset($_GET['id']))
{
  if(isset($_GET['delete']))
  {
    $query = 'UPDATE courses SET
      image = ""
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    $result = mysqli_query($connect, $query);
    
    set_message('Course image has been deleted');
    header('Location: courses.php');
    die();
  }
  
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
include 'includes/wideimage/WideImage.php';
?>

<h2>Edit Course Image</h2>

<p>Note: For best results, images should be approximately 800 x 450 pixels (16:9 aspect ratio).</p>

<?php if($record['image']): ?>
  <?php
  $data = base64_decode(explode(',', $record['image'])[1]);
  $img = WideImage::loadFromString($data);
  $data = $img->resize(400, 225, 'outside')->crop('center', 'center', 400, 225)->asString('jpg', 70);
  ?>
  <p><img src="data:image/jpg;base64,<?php echo base64_encode($data); ?>" width="400" height="225"></p>
  <p><a href="courses_photo.php?id=<?php echo $_GET['id']; ?>&delete"><i class="fas fa-trash-alt"></i> Delete this Image</a></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <label for="image">Image:</label>
  <input type="file" name="image" id="image" accept="image/*">
  <br>
  <input type="submit" value="Save Image">
</form>

<p><a href="courses.php"><i class="fas fa-arrow-circle-left"></i> Return to Course List</a></p>

<?php
include('includes/footer.php');
?>