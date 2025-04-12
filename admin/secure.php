<?php
// Secure function for frontend users
function secure() {
  if(!isset($_SESSION['user_id'])) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    set_message('Please login to access this page', 'error');
    header('Location: login.php');
    exit;
  }

}
?>