<?php
// Create a 200x200 image
$image = imagecreatetruecolor(200, 200);

// Set background color (light gray)
$bg_color = imagecolorallocate($image, 240, 240, 240);
imagefill($image, 0, 0, $bg_color);

// Set text color (dark gray)
$text_color = imagecolorallocate($image, 100, 100, 100);

// Draw a circle for the avatar background
$circle_color = imagecolorallocate($image, 200, 200, 200);
imagefilledellipse($image, 100, 100, 180, 180, $circle_color);

// Add a simple user icon
$icon_color = imagecolorallocate($image, 150, 150, 150);
// Draw head
imagefilledellipse($image, 100, 80, 40, 40, $icon_color);
// Draw body
$points = array(
    100, 120,  // Top
    70, 160,   // Left
    130, 160   // Right
);
imagefilledpolygon($image, $points, 3, $icon_color);

// Save the image
imagepng($image, 'default-avatar.png');
imagedestroy($image);

echo "Default avatar generated successfully!";
?> 