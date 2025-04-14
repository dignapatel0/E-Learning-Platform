<?php

$connect = mysqli_connect( 
    "localhost", // Host
    "root", // Username
    "", // Password
    "e_learning" // Database
);

mysqli_set_charset( $connect, 'UTF8' );
