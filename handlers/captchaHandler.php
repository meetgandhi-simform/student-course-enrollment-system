<?php
session_start();

// $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz23456789';
$characters = '1';
$captchaText = '';

for ($i = 0; $i < 5; $i++) {
    $captchaText .= $characters[rand(0, strlen($characters) - 1)];
}

$_SESSION['captcha'] = $captchaText;

$image = imagecreate(130, 45);

$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);
$noiseColor = imagecolorallocate($image, 120, 120, 120);

imagefilledrectangle($image, 0, 0, 130, 45, $bgColor);

for ($i = 0; $i < 300; $i++) {
    imagesetpixel($image, rand(0, 130), rand(0, 45), $noiseColor);
}

for ($i = 0; $i < 5; $i++) {
    imageline($image, rand(0, 130), rand(0, 45), rand(0, 130), rand(0, 45), $noiseColor);
}

$x = 5;

for ($i = 0; $i < strlen($captchaText); $i++) {
    $y = rand(10, 25);
    imagestring($image, 4, $x, $y, $captchaText[$i], $textColor);
    $x += 22;
}

header("Content-Type: image/png");
imagepng($image);
