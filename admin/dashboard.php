<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

?>

<ul id="dashboard">
  <li>
    <a href="courses.php">
      <i class="fas fa-book"></i> Manage Courses
    </a>
  </li>
  <li>
    <a href="lessons.php">
      <i class="fas fa-video"></i> Manage Lessons
    </a>
  </li>
  <li>
    <a href="instructors.php">
      <i class="fas fa-chalkboard-teacher"></i> Manage Instructors
    </a>
  </li>
  <li>
    <a href="users.php">
      Manage Users
    </a>
  </li>
  <li>
    <a href="logout.php">
      Logout
    </a>
  </li>
</ul>

<?php

include( 'includes/footer.php' );

?>