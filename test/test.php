<?php
require_once __DIR__ . '/../vendor/autoload.php';

$time = '2023-09-06T10:00:00+0000';

echo date('Y-m-d H:i:s', strtotime($time)).PHP_EOL;
