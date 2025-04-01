<?php
session_start();

// Генерация капчи, если её нет в сессии
if (empty($_SESSION['captcha'])) {
    $_SESSION['captcha'] = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
}

// Параметры изображения
header('Content-Type: image/png');
$image_width = 250;
$image_height = 120;
$font_size = 30;
$font_file = __DIR__ . '/../fonts/addelle.ttf';

// Создание изображения и установка цветов
$image = imagecreate($image_width, $image_height);
imagecolorallocate($image, 255, 255, 255); // Фон (первый цвет становится фоном)
$text_color = imagecolorallocate($image, 0, 0, 0);

// Отрисовка текста
if (file_exists($font_file)) {
    $textbox = imagettfbbox($font_size, 0, $font_file, $_SESSION['captcha']);
    $x = ($image_width - ($textbox[2] - $textbox[0])) / 2;
    $y = ($image_height + ($textbox[1] - $textbox[7])) / 2;
    imagettftext($image, $font_size, rand(-10, 10), $x, $y, $text_color, $font_file, $_SESSION['captcha']);
} else {
    imagestring($image, 5, 50, 60, $_SESSION['captcha'], $text_color);
}

// Вывод и очистка
imagepng($image);
imagedestroy($image);
