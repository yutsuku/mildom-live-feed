<?php
declare(strict_types=1);

include './vendor/autoload.php';
$config = include './config.php';

define('APP_ROOT', __DIR__);

include 'src/app.php';
