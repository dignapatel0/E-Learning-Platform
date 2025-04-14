
<?php

include(__DIR__ . '/load_env.php');

$connect = mysqli_connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
    $_ENV['DB_DATABASE']
);

mysqli_set_charset($connect, $_ENV['DB_CHARSET']);
