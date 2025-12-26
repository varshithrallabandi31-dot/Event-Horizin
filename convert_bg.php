<?php
$src = 'public/assets/images/certificate_bg.png';
$dst = 'public/assets/images/certificate_bg.jpg';
if (!file_exists($src)) {
    die("Source not found: $src");
}
$image = imagecreatefrompng($src);
if (!$image) {
    die("Failed to load PNG");
}
imagejpeg($image, $dst, 90);
imagedestroy($image);
echo "Converted to JPG";
?>
