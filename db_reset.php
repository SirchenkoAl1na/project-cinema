<?php

require_once __DIR__ . '/vendor/autoload.php';
session_start();
use App\Migration;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB reset</title>
</head>

<body>
    <?php
    Migration::reset();
    echo "<h1>Database refreshed</h1>";
    session_destroy();
    echo "Fill new database with test data<br>";
    Migration::run();
    ?>
    <a href="/">to main page</a>
</body>

</html>