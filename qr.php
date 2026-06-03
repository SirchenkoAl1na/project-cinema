<?php
require 'vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$token = $_GET['token'] ?? '';

$options = new QROptions([
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
]);

header('Content-Type: image/png');

echo (new QRCode($options))->render($token);