<?php
function is_user_enrolled($user_id, $course_id) {
    global $connect;
    $query = 'SELECT id FROM enrollments 
              WHERE user_id = '.$user_id.' 
              AND course_id = '.$course_id;
    $result = mysqli_query($connect, $query);
    return mysqli_num_rows($result) > 0;
}

function get_enrollment_status($user_id, $course_id) {
    global $connect;
    $query = 'SELECT completed FROM enrollments 
              WHERE user_id = '.$user_id.' 
              AND course_id = '.$course_id;
    $result = mysqli_query($connect, $query);
    if(mysqli_num_rows($result)) {
        $record = mysqli_fetch_assoc($result);
        return $record['completed'];
    }
    return false;
}

function get_user_enrollments($user_id, $limit = null) {
    global $connect;
    $query = 'SELECT e.*, c.title, c.image 
              FROM enrollments e
              JOIN courses c ON e.course_id = c.id
              WHERE e.user_id = '.$user_id.'
              ORDER BY e.enrolled_at DESC';
              
    if($limit) $query .= ' LIMIT '.$limit;
    
    $result = mysqli_query($connect, $query);
    $enrollments = array();
    while($record = mysqli_fetch_assoc($result)) {
        $enrollments[] = $record;
    }
    return $enrollments;
}
?>