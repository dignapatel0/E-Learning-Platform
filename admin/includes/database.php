<?php

$connect = mysqli_connect( 
    "localhost", // Host
    "root", // Username
    "", // Password
    "e-learning" // Database
);

mysqli_set_charset( $connect, 'UTF8' );
