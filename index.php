
<?php
include('admin/includes/database.php');
include('admin/includes/config.php');
include('admin/includes/functions.php');

// Fetch courses data
$query = "SELECT * FROM courses ORDER BY title ASC LIMIT 3"; // Limit to 3 for homepage
$courses_result = mysqli_query($connect, $query);

// Fetch instructors data
$query_instructors = "SELECT * FROM instructors ORDER BY name ASC LIMIT 3";
$instructors_result = mysqli_query($connect, $query_instructors);

?>

<div class="container">
    <h2 class="my-4">Featured Courses</h2>
    <div class="row">
        <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <?php if (!empty($course['image'])): ?>
                    <img src="<?= htmlspecialchars($course['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($course['title']) ?>">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                    <a href="course_details.php?id=<?= $course['id'] ?>" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <h2 class="my-4">Our Instructors</h2>
    <div class="row">
        <?php while ($instructor = mysqli_fetch_assoc($instructors_result)): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <?php if (!empty($instructor['photo'])): ?>
                    <img src="<?= htmlspecialchars($instructor['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($instructor['name']) ?>">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($instructor['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars(substr($instructor['bio'], 0, 100)) ?>...</p>
                    <a href="instructor_details.php?id=<?= $instructor['id'] ?>" class="btn btn-primary">View Profile</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('admin/includes/footer.php'); ?>
