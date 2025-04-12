<?php
include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(!isset($_GET['id'])) {
    header('Location: enrollments.php');
    die();
}

if(isset($_POST['user_id'])) {
    $query = 'UPDATE enrollments SET
        user_id = '.$_POST['user_id'].',
        course_id = '.$_POST['course_id'].',
        completed = "'.$_POST['completed'].'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query($connect, $query);
    
    set_message('Enrollment has been updated');
    header('Location: enrollments.php');
    die();
}

if(isset($_GET['id'])) {
    $query = 'SELECT * FROM enrollments WHERE id = '.$_GET['id'].' LIMIT 1';
    $result = mysqli_query($connect, $query);
    
    if(!mysqli_num_rows($result)) {
        header('Location: enrollments.php');
        die();
    }
    
    $record = mysqli_fetch_assoc($result);
}

include('includes/header.php');
?>

<h2>Edit Enrollment</h2>

<form method="post">
    <label for="user_id">User:</label>
    <?php
    $query = 'SELECT * FROM users ORDER BY email';
    $result = mysqli_query($connect, $query);
    ?>
    <select name="user_id" id="user_id">
        <?php while($user = mysqli_fetch_assoc($result)): ?>
            <option value="<?php echo $user['id']; ?>"
                <?= ($user['id'] == $record['user_id']) ? 'selected' : '' ?>>
                <?php echo $user['email']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <br>
    
    <label for="course_id">Course:</label>
    <?php
    $query = 'SELECT * FROM courses ORDER BY title';
    $result = mysqli_query($connect, $query);
    ?>
    <select name="course_id" id="course_id">
        <?php while($course = mysqli_fetch_assoc($result)): ?>
            <option value="<?php echo $course['id']; ?>"
                <?= ($course['id'] == $record['course_id']) ? 'selected' : '' ?>>
                <?php echo $course['title']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <br>
    
    <label for="completed">Status:</label>
    <select name="completed" id="completed">
        <option value="No" <?= ($record['completed'] == 'No') ? 'selected' : '' ?>>In Progress</option>
        <option value="Yes" <?= ($record['completed'] == 'Yes') ? 'selected' : '' ?>>Completed</option>
    </select>
    
    <br>
    
    <input type="submit" value="Update Enrollment">
</form>

<p><a href="enrollments.php"><i class="fas fa-arrow-circle-left"></i> Return to Enrollment List</a></p>

<?php include('includes/footer.php'); ?>