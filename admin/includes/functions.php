<?php

function curl_get_contents( $url )
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function pre( $data )
{
  
  echo '<pre>';
  print_r( $data );
  echo '</pre>';
  
}

function secure()
{
  
  if( !isset( $_SESSION['id'] ) )
  {
    
    header( 'Location: /' );
    die();
    
  }
  
}

function set_message( $message )
{
  
  $_SESSION['message'] = $message;
  
}

function get_message()
{
  
  if( isset( $_SESSION['message'] ) )
  {
    
    echo '<p style="padding: 0 1%;" class="error">
        <i class="fas fa-exclamation-circle"></i> 
        '.$_SESSION['message'].'
      </p>
      <hr>';
    unset( $_SESSION['message'] );
    
  }
  
}

function send_email($to, $subject, $message) {
  $headers = "From: no-reply@yourdomain.com\r\n";
  $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  
  return mail($to, $subject, $message, $headers);
}
function display_messages() {
  if (isset($_SESSION['message'])) {
      echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
      unset($_SESSION['message']);
  }
  
  if (isset($_SESSION['error'])) {
      echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
      unset($_SESSION['error']);
  }
}
function display_html_content($content, $default = '') {
  if (empty($content)) {
      return '<p class="text-muted">' . htmlspecialchars($default) . '</p>';
  }
  
  $allowed_tags = '<p><br><strong><em><u><ol><ul><li><a><img><h1><h2><h3><h4><h5><h6>';
  $clean_content = strip_tags($content, $allowed_tags);
  
  if (strip_tags($clean_content) === $clean_content) {
      return nl2br(htmlspecialchars($clean_content));
  }
  
  return $clean_content;
}