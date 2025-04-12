<?php
include('../../includes/database.php');
include('../../includes/config.php');
include('../../includes/functions.php');

secure();

if(isset($_POST['user_id'])) {
    $query = 'INSERT INTO enrollments (
        user_id,
        course_id,
        completed
      ) VALUES (
        '.$_POST['user_id'].',
        '.$_POST['course_id'].',
        "'.$_POST['completed'].'"
      )';
    mysqli_query($connect, $query);
    
    set_message('Enrollment has been added');
    header('Location: enrollments.php');
    die();
}

include('../../includes/header.php');
?>

<h2>Add Enrollment</h2>

<form method="post">
    <label for="user_id">User:</label>
    <?php
    $query = 'SELECT * FROM users ORDER BY email';
    $result = mysqli_query($connect, $query);
    ?>
    <select name="user_id" id="user_id">
        <?php while($user = mysqli_fetch_assoc($result)): ?>
            <option value="<?php echo $user['id']; ?>">
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
            <option value="<?php echo $course['id']; ?>">
                <?php echo $course['title']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <br>
    
    <label for="completed">Status:</label>
    <select name="completed" id="completed">
        <option value="No">In Progress</option>
        <option value="Yes">Completed</option>
    </select>
    
    <br>
    
    <input type="submit" value="Add Enrollment">
</form>

<p><a href="enrollments.php"><i class="fas fa-arrow-circle-left"></i> Return to Enrollment List</a></p>

<?php include('../../includes/footer.php'); ?>