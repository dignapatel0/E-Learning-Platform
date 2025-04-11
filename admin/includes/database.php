<?php

$connect = mysqli_connect( 
    "localhost", // Host
    "root", // Username
    "", // Password
    "php_cms" // Database
);

mysqli_set_charset( $connect, 'UTF8' );
