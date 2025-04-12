<?php

session_start();

// Add these to your config.php
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('ADMIN_URL', BASE_URL . 'admin/');
const SITE_NAME = 'E-Learning Platform';

header( 'Content-type: text/html; charset=utf-8' );
